import { MessageManager } from '../utils/MessageManager.js'

export class ApiManager {
  constructor() {
    this.messageManager = new MessageManager()
    this.tokenInput = document.getElementById('token')
    this.jwt = localStorage.getItem('jwtToken')
  }

  addApiKeys() {
    const button = document.getElementById('submitApiKeys')

    if (button) {
      button.addEventListener('click', async (e) => {
        try {
          e.preventDefault(e)

          const apiGoogleKey = document.getElementById('apiGoogleKey').value
          const apiGooglePlaceId = document.getElementById('apiGooglePlaceId').value
          const token = this.tokenInput.value
          const jwt = this.jwt

          if (!apiGoogleKey || !apiGooglePlaceId) {
            const message = 'Veuillez remplir tous les champs.'
            throw new Error(message)
          }

          if (!token || !jwt) {
            const message = "Problème d'authentification"
            throw new Error(message)
          }

          const addData = new FormData()
          addData.append('apiGoogleKey', apiGoogleKey)
          addData.append('apiGooglePlaceId', apiGooglePlaceId)
          addData.append('token', token)

          const response = await fetch('index.php?controller=api&action=add', {
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
              window.location.href = 'index.php?controller=api&action=googleApi'
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
        }
      })
    }
  }

  deleteApiKeys() {
    const deleteButton = document.getElementById('deleteApiKeys')

    if (deleteButton) {
      deleteButton.addEventListener('click', async () => {
        try {
          const token = this.tokenInput.value
          const jwt = this.jwt

          if (!token || !jwt) {
            const message = "Problème d'authentification"
            throw new Error(message)
          }

          const deleteData = new FormData()
          deleteData.append('token', token)

          const response = await fetch('index.php?controller=api&action=delete', {
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
              window.location.href = 'index.php?controller=api&action=googleApi'
            }, 2000)
          } else {
            if (response.status === 401) {
              this.messageManager.refreshMessage(data, 'messageContainer')
            } else if (response.status === 403) {
              window.location.href = 'index.php?controller=auth'
            } else if (response.status === 409) {
              this.messageManager.failedMessage(data, 'messageContainer')
            }
          }
        } catch (error) {
          this.messageManager.errorMessage(error, 'messageContainer')
        }
      })
    }
  }

  editApiKeys() {
    const editButton = document.getElementById('editApiKeys')
    if (editButton) {
      editButton.addEventListener('click', async () => {
        try {
          const api_idInput = document.getElementById('api_id')
          const apiGoogleKeyInput = document.getElementById('apiGoogleKey')
          const apiGooglePlaceIdInput = document.getElementById('apiGooglePlaceId')

          const api_id = api_idInput.value
          const apiGoogleKey = apiGoogleKeyInput.value
          const apiGooglePlaceId = apiGooglePlaceIdInput.value
          const token = this.tokenInput.value
          const jwt = this.jwt

          if (!apiGoogleKey || !apiGooglePlaceId) {
            const message = 'Veuillez remplir tous les champs.'
            throw new Error(message)
          }

          if (!token || !jwt) {
            const message = "Problème d'authentification"
            throw new Error(message)
          }

          const updateData = new FormData()
          updateData.append('api_id', api_id)
          updateData.append('apiGoogleKey', apiGoogleKey)
          updateData.append('apiGooglePlaceId', apiGooglePlaceId)
          updateData.append('token', token)

          const response = await fetch('index.php?controller=api&action=edit', {
            method: 'POST',
            body: updateData,
            headers: {
              Authorization: `Bearer ${jwt}`,
            },
          })
          const data = await response.json()

          if (response.status === 200) {
            this.messageManager.successMessage(data, 'messageContainer')
            setTimeout(function () {
              window.location.href = 'index.php?controller=api&action=review'
            }, 2000)
          } else {
            if (response.status === 401) {
              this.messageManager.refreshMessage(data, 'messageContainer')
            } else if (response.status === 403) {
              window.location.href = 'index.php?controller=auth'
            } else if (response.status === 400 || response.status === 500) {
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
