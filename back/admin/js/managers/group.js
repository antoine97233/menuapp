import { MessageManager } from '../utils/MessageManager.js'

export class GroupManager {
  constructor(groupToEditId) {
    this.groupToEditId = groupToEditId
    this.messageManager = new MessageManager()

    this.groupTitleInput = document.getElementById('groupTitle')
    this.groupDescriptionInput = document.getElementById('groupDescription')
    this.tokenInput = document.getElementById('token')
    this.jwt = localStorage.getItem('jwtToken')
  }

  addGroup() {
    const addButton = document.getElementById('submitGroup')
    if (addButton) {
      addButton.addEventListener('click', async () => {
        try {
          const groupTitle = this.groupTitleInput.value
          const groupDescription = this.groupDescriptionInput.value
          const token = this.tokenInput.value
          const jwt = this.jwt

          if (!groupTitle || !groupDescription) {
            const message = 'Veuillez remplir tous les champs.'
            throw new Error(message)
          }

          if (!token || !jwt) {
            const message = "Problème d'authentification"
            throw new Error(message)
          }

          const addData = new FormData()
          addData.append('groupTitle', groupTitle)
          addData.append('groupDescription', groupDescription)
          addData.append('token', token)

          const response = await fetch('index.php?controller=group&action=add', {
            method: 'POST',
            body: addData,
            headers: {
              Authorization: `Bearer ${jwt}`,
            },
          })

          const data = await response.json()

          if (response.status === 200) {
            this.groupTitleInput.value = ''
            this.groupDescriptionInput.value = ''

            const groupBlock = data.groupBlock
            const groupList = data.groupList

            document.getElementById('displayGroup').insertAdjacentHTML('beforeend', groupBlock)
            document
              .getElementById('displayBulletPointGroup')
              .insertAdjacentHTML('beforeend', groupList)

            this.messageManager.successMessage(data, 'messageContainer')

            this.deleteGroup()
            this.editGroup()
            this.updateGroup()
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

  deleteGroup() {
    const buttons = document.querySelectorAll('.deleteGroup')
    for (const btn of buttons) {
      btn.removeEventListener('click', this.deleteGroupListener)
      btn.addEventListener('click', this.deleteGroupListener)
    }
  }

  deleteGroupListener = async (event) => {
    try {
      const btn = event.currentTarget
      const groupId = btn.dataset.id
      const token = this.tokenInput.value
      const jwt = this.jwt

      if (!token || !jwt) {
        const message = "Problème d'authentification"
        throw new Error(message)
      }

      const deleteData = new FormData()
      deleteData.append('groupId', groupId)
      deleteData.append('token', token)

      const response = await fetch('index.php?controller=group&action=delete', {
        method: 'POST',
        body: deleteData,
        headers: {
          Authorization: `Bearer ${jwt}`,
        },
      })

      const data = await response.json()

      if (response.status === 200) {
        document.getElementById('pushGroup' + data.groupId).remove()
        document.getElementById('bulletedGroupList' + data.groupId).remove()

        this.groupTitleInput.value = ''
        this.groupDescriptionInput.value = ''

        document.getElementById('submitGroup').style.display = 'block'
        document.getElementById('updateGroup').style.display = 'none'

        this.messageManager.successMessage(data, 'messageContainer')
      } else {
        if (response.status === 401) {
          this.messageManager.refreshMessage(data, 'messageContainer')
        } else if (response.status === 403) {
          window.location.href = 'index.php?controller=auth'
        } else if (response.status === 409 || response.status === 500) {
          this.messageManager.failedMessage(data, 'messageContainer')
        }
      }
    } catch (error) {
      this.messageManager.errorMessage(error, 'messageContainer')
    }
  }

  editGroup() {
    const buttons = document.querySelectorAll('.editGroup')
    for (const btn of buttons) {
      btn.addEventListener('click', () => {
        window.scrollTo(0, 0)

        this.groupToEditId = btn.dataset.id
        const divGroup = btn.parentNode.parentNode
        const groupTitle = divGroup.querySelector('h4')
        const groupDescription = divGroup.querySelector('p')

        this.groupTitleInput.value = groupTitle.textContent
        this.groupDescriptionInput.value = groupDescription.textContent

        document.getElementById('actionGroup').textContent = 'Modifier'
        document.getElementById('submitGroup').style.display = 'none'
        document.getElementById('updateGroup').style.display = 'block'
      })
    }
  }

  updateGroup() {
    const updateGroup = document.getElementById('updateGroup')
    if (updateGroup) {
      updateGroup.removeEventListener('click', this.updateGroupListener)
      updateGroup.addEventListener('click', this.updateGroupListener)
    }
  }

  updateGroupListener = async () => {
    try {
      const groupId = this.groupToEditId

      const groupTitle = this.groupTitleInput.value
      const groupDescription = this.groupDescriptionInput.value
      const token = this.tokenInput.value
      const jwt = this.jwt

      if (!groupTitle || !groupDescription) {
        const message = 'Veuillez remplir tous les champs.'
        throw new Error(message)
      }

      if (!token || !jwt) {
        const message = "Problème d'authentification"
        throw new Error(message)
      }

      const updateData = new FormData()
      updateData.append('groupId', groupId)
      updateData.append('groupTitle', groupTitle)
      updateData.append('groupDescription', groupDescription)
      updateData.append('token', token)

      const response = await fetch('index.php?controller=group&action=edit', {
        method: 'POST',
        body: updateData,
        headers: {
          Authorization: `Bearer ${jwt}`,
        },
      })
      const data = await response.json()

      if (response.status === 200) {
        document.getElementById('actionGroup').textContent = 'Ajouter'
        document.getElementById('submitGroup').style.display = 'block'
        document.getElementById('updateGroup').style.display = 'none'

        this.groupTitleInput.value = ''
        this.groupDescriptionInput.value = ''

        document.getElementById('title' + data.groupId).textContent = data.groupTitle
        document.getElementById('desc' + data.groupId).textContent = data.groupDescription
        document.getElementById('redirect' + data.groupId).href = data.groupUrl

        const groupBulletElement = document.getElementById('bulletedGroupList' + data.groupId)
        groupBulletElement.querySelector('h5').textContent = data.groupTitle

        this.messageManager.successMessage(data, 'messageContainer')
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
  }
}
