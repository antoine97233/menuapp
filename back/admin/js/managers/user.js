import { MessageManager } from '../utils/MessageManager.js'

export class UserManager {
  constructor() {
    this.messageManager = new MessageManager()
    this.adminEmailInput = document.getElementById('adminEmail')
    this.adminNameInput = document.getElementById('adminName')
    this.adminPasswordInput = document.getElementById('adminPassword')
    this.tokenInput = document.getElementById('token')
    this.jwt = localStorage.getItem('jwtToken')
  }

  addUser() {
    const addButton = document.getElementById('addUser')
    if (addButton) {
      addButton.addEventListener('click', async () => {
        try {
          const adminEmail = this.adminEmailInput.value
          const adminName = this.adminNameInput.value
          const adminPassword = this.adminPasswordInput.value
          const token = this.tokenInput.value
          const jwt = this.jwt

          if (!this.adminEmailInput.checkValidity()) {
            throw new Error('Veuillez saisir une adresse e-mail valide.')
          }

          if (!adminEmail || !adminName || !adminPassword) {
            const message = 'Veuillez remplir tous les champs.'
            throw new Error(message)
          }

          if (!token || !jwt) {
            const message = "Problème d'authentification"
            throw new Error(message)
          }

          const addData = new FormData()
          addData.append('adminEmail', adminEmail)
          addData.append('adminName', adminName)
          addData.append('adminPassword', adminPassword)
          addData.append('token', token)

          const response = await fetch('index.php?controller=user&action=add', {
            method: 'POST',
            body: addData,
            headers: {
              Authorization: `Bearer ${jwt}`,
            },
          })

          const data = await response.json()

          if (response.status === 200) {
            this.messageManager.successMessage(data, 'messageContainer')
            setTimeout(function () {
              window.location.href = 'index.php?controller=user'
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

  deleteUser() {
    const deleteButton = document.getElementById('deleteUser')
    if (deleteButton) {
      deleteButton.addEventListener('click', async () => {
        try {
          const adminId = document.getElementById('adminId').value
          const adminEmail = this.adminEmailInput.value
          const token = this.tokenInput.value
          const jwt = this.jwt

          if (!token || !jwt) {
            const message = "Problème d'authentification"
            throw new Error(message)
          }

          const deleteData = new FormData()
          deleteData.append('adminId', adminId)
          deleteData.append('adminEmail', adminEmail)
          deleteData.append('token', token)

          const response = await fetch('index.php?controller=user&action=delete', {
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
              window.location.href = 'index.php?controller=user'
            }, 2000)
          } else {
            if (response.status === 401) {
              if (data.refresh) {
                this.messageManager.refreshMessage(data, 'messageContainer')
              } else {
                window.location.href = 'index.php?controller=user'
              }
            } else if (response.status === 403) {
              window.location.href = 'index.php?controller=auth'
            } else if (response.status === 500) {
              this.messageManager.failedMessage(data, 'messageContainer')
            }
          }
        } catch (error) {
          this.messageManager.errorMessage(error, 'messageContainer')
        }
      })
    }
  }
}
