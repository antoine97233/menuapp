import { MessageManager } from '../utils/MessageManager.js'

export class MenuManager {
  constructor() {
    this.messageManager = new MessageManager()

    this.menuTitleInput = document.getElementById('inputMenuTitle')
    this.menuFileInput = document.getElementById('inputMenuPdf')

    this.tokenInput = document.getElementById('token')
    this.jwt = localStorage.getItem('jwtToken')
  }

  addMenu() {
    const addButton = document.getElementById('addMenu')
    if (addButton) {
      addButton.addEventListener('click', async () => {
        try {
          const menuTitle = this.menuTitleInput.value
          const token = this.tokenInput.value
          const jwt = this.jwt

          if (!menuTitle) {
            const message = 'Veuillez remplir tous les champs.'
            throw new Error(message)
          }

          if (!token || !jwt) {
            const message = "Problème d'authentification"
            throw new Error(message)
          }

          const addData = new FormData()
          addData.append('menuTitle', menuTitle)
          addData.append('token', token)

          if (this.menuFileInput.files.length <= 0) {
            const message = 'Veuillez ajouter un fichier PDF'
            throw new Error(message)
          }

          const inputMenuFile = this.menuFileInput.files[0]
          addData.append('inputMenuFile', inputMenuFile)

          const response = await fetch('index.php?controller=menu&action=add', {
            method: 'POST',
            body: addData,
            headers: {
              Authorization: `Bearer ${jwt}`,
            },
          })

          const data = await response.json()

          if (response.status === 200) {
            console.log(data)
            this.messageManager.successMessage(data, 'messageContainer')
            setTimeout(function () {
              window.location.href = 'index.php?controller=menu'
            }, 2000)
          } else {
            if (response.status === 401) {
              this.messageManager.refreshMessage(data, 'messageContainer')
            } else if (response.status === 403) {
              window.location.href = 'index.php?controller=auth'
            } else {
              this.messageManager.failedMessage(data, 'messageContainer')
            }
          }
        } catch (error) {
          this.messageManager.errorMessage(error, 'messageContainer')
        }
      })
    }
  }

  deleteMenu() {
    const deleteButton = document.getElementById('deleteMenu')
    if (deleteButton) {
      deleteButton.addEventListener('click', async () => {
        try {
          console.log('delete')

          const menuId = document.getElementById('inputMenuId').value
          const menuTitle = this.menuTitleInput.value
          const token = this.tokenInput.value
          const jwt = this.jwt

          if (!token || !jwt) {
            const message = "Problème d'authentification"
            throw new Error(message)
          }

          const deleteData = new FormData()
          deleteData.append('menuId', menuId)
          deleteData.append('menuTitle', menuTitle)
          deleteData.append('token', token)

          const response = await fetch('index.php?controller=menu&action=delete', {
            method: 'POST',
            body: deleteData,
            headers: {
              Authorization: `Bearer ${jwt}`,
            },
          })

          const data = await response.json()

          if (response.status === 200) {
            this.messageManager.successMessage(data, 'messageContainer')
            setTimeout(function () {
              window.location.href = 'index.php?controller=menu'
            }, 2000)
          } else {
            if (response.status === 401) {
              this.messageManager.refreshMessage(data, 'messageContainer')
            } else if (response.status === 403) {
              window.location.href = 'index.php?controller=auth'
            } else if (response.status === 500) {
              this.messageManager.failedMessage(data, 'messageContainer')
            }
          }
        } catch (error) {
          this.messageManager.errorMessage(error, 'messageContainer')
          console.log(error)
        }
      })
    }
  }
}
