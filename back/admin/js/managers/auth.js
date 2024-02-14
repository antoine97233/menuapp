import { MessageManager } from '../utils/MessageManager.js'

export class AuthManager {
  constructor() {
    this.messageManager = new MessageManager()
  }

  login() {
    const button = document.getElementById('login')
    const passwordInput = document.getElementById('adminPassword')

    if (button && passwordInput) {
      button.addEventListener('click', async (e) => {
        try {
          e.preventDefault()

          const adminEmail = document.getElementById('adminEmail').value
          const adminPassword = passwordInput.value
          const token = document.getElementById('token').value

          const addData = new FormData()
          addData.append('adminEmail', adminEmail)
          addData.append('adminPassword', adminPassword)
          addData.append('token', token)

          const response = await fetch('index.php?controller=auth&action=login', {
            method: 'POST',
            body: addData,
          })

          const data = await response.json()

          if (response.status === 200) {
            const jwtToken = data.jwtToken
            localStorage.setItem('jwtToken', jwtToken)
            window.location.href = 'index.php?controller=home'
          } else {
            this.messageManager.failedMessage(data, 'messageContainer')
          }
        } catch (error) {
          this.messageManager.errorMessage(error, 'messageContainer')
        }
      })

      passwordInput.addEventListener('keypress', async (e) => {
        if (e.key === 'Enter') {
          e.preventDefault()

          try {
            const adminEmail = document.getElementById('adminEmail').value
            const adminPassword = passwordInput.value
            const token = document.getElementById('token').value

            const addData = new FormData()
            addData.append('adminEmail', adminEmail)
            addData.append('adminPassword', adminPassword)
            addData.append('token', token)

            const response = await fetch('index.php?controller=auth&action=login', {
              method: 'POST',
              body: addData,
            })

            const data = await response.json()

            if (response.status === 200) {
              const jwtToken = data.jwtToken
              localStorage.setItem('jwtToken', jwtToken)
              window.location.href = 'index.php?controller=home'
            } else {
              this.messageManager.failedMessage(data, 'messageContainer')
            }
          } catch (error) {
            this.messageManager.errorMessage(error, 'messageContainer')
          }
        }
      })
    }
  }
}
