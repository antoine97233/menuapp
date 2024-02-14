import { MessageManager } from '../utils/MessageManager.js'

export class ItemManager {
  constructor() {
    this.messageManager = new MessageManager()

    this.tokenInput = document.getElementById('token')
    this.jwt = localStorage.getItem('jwtToken')
  }

  addImage() {
    const addButton = document.getElementById('inputItemImage')
    if (addButton) {
      addButton.addEventListener('change', async () => {
        const input = document.getElementById('inputItemImage')
        const itemImagePreview = document.getElementById('itemImagePreview')

        const file = input.files[0]
        const reader = new FileReader()

        reader.addEventListener('load', function () {
          try {
            if (reader.result.startsWith('data:image')) {
              itemImagePreview.setAttribute('src', reader.result)
            } else {
              throw new Error("Le fichier sélectionné n'est pas une image valide.")
            }
          } catch (error) {
            this.messageManager.errorMessage(error, 'messageContainer')
          }
        })

        reader.readAsDataURL(file)
      })
    }
  }

  addItem() {
    const addButton = document.getElementById('addItem')
    if (addButton) {
      addButton.addEventListener('click', async () => {
        try {
          const itemTitleInput = document.getElementById('inputItemTitle')
          const itemDescriptionInput = document.getElementById('inputItemDescription')
          const itemPriceInput = document.getElementById('inputItemPrice')
          const itemStockInput = document.getElementById('inputItemStock')
          const itemImageInput = document.getElementById('inputItemImage')
          const categoryIdInput = document.getElementById('inputCategoryId')

          const itemTitle = itemTitleInput.value
          const itemDescription = itemDescriptionInput.value
          const itemPrice = itemPriceInput.value
          const itemStock = itemStockInput.value
          const categoryId = categoryIdInput.value
          const token = this.tokenInput.value
          const jwt = this.jwt

          if (!itemTitle || !itemDescription) {
            const message = 'Veuillez remplir tous les champs.'
            throw new Error(message)
          }

          if (!itemStockInput.checkValidity() || itemStockInput.value < 0) {
            throw new Error('Champ stock non valide')
          }

          if (!token || !jwt) {
            const message = "Problème d'authentification"
            throw new Error(message)
          }

          const addData = new FormData()
          addData.append('itemTitle', itemTitle)
          addData.append('itemDescription', itemDescription)
          addData.append('itemPrice', itemPrice)
          addData.append('itemStock', itemStock)
          addData.append('categoryId', categoryId)
          addData.append('token', token)

          if (itemImageInput.files.length <= 0) {
            const message = 'Veuillez ajouter une image'
            throw new Error(message)
          }

          const inputImageItem = itemImageInput.files[0]
          addData.append('inputImageItem', inputImageItem)

          const response = await fetch('index.php?controller=item&action=add', {
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
              const urlParams = new URLSearchParams(window.location.search)
              const groupId = urlParams.get('groupId')

              window.location.href = 'index.php?controller=category&groupId=' + groupId
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

  deleteItem() {
    const buttons = document.querySelectorAll('.deleteItem')
    for (const btn of buttons) {
      btn.removeEventListener('click', this.deleteItemListener)
      btn.addEventListener('click', this.deleteItemListener)
    }
  }

  deleteItemListener = async (event) => {
    try {
      const btn = event.currentTarget
      const itemId = btn.dataset.id
      const itemTitle = document.getElementById('itemTitle' + itemId).textContent
      const categoryId = btn.dataset.categoryid
      const token = this.tokenInput.value
      const jwt = this.jwt

      if (!token || !jwt) {
        const message = "Problème d'authentification"
        throw new Error(message)
      }

      const deleteData = new FormData()
      deleteData.append('itemId', itemId)
      deleteData.append('itemTitle', itemTitle)
      deleteData.append('categoryId', categoryId)
      deleteData.append('token', token)

      const response = await fetch('index.php?controller=item&action=delete', {
        method: 'POST',
        body: deleteData,
        headers: {
          Authorization: `Bearer ${jwt}`,
        },
      })

      const data = await response.json()
      if (response.status === 200) {
        document.getElementById('pushItem' + data.itemId).remove()
        this.messageManager.successMessage(data, 'messageContainer')
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
  }

  updateItem() {
    const button = document.getElementById('updateItem')
    if (button) {
      button.addEventListener('click', async () => {
        try {
          const itemTitleInput = document.getElementById('inputItemTitle')
          const itemDescriptionInput = document.getElementById('inputItemDescription')
          const itemPriceInput = document.getElementById('inputItemPrice')
          const itemStockInput = document.getElementById('inputItemStock')
          const itemImageInput = document.getElementById('inputItemImage')
          const categoryIdInput = document.getElementById('inputCategoryId')
          const itemIdInput = document.getElementById('inputItemId')

          const itemId = itemIdInput.value
          const itemTitle = itemTitleInput.value
          const itemDescription = itemDescriptionInput.value
          const itemPrice = itemPriceInput.value
          const itemStock = itemStockInput.value
          const categoryId = categoryIdInput.value
          const token = this.tokenInput.value
          const jwt = this.jwt

          if (!itemStockInput.checkValidity() || itemStockInput.value < 0) {
            throw new Error('Champ stock non valide')
          }

          if (!itemTitle || !itemDescription) {
            const message = 'Veuillez remplir tous les champs.'
            throw new Error(message)
          }

          if (!token || !jwt) {
            const message = "Problème d'authentification"
            throw new Error(message)
          }

          const updateData = new FormData()
          updateData.append('itemTitle', itemTitle)
          updateData.append('itemDescription', itemDescription)
          updateData.append('itemPrice', itemPrice)
          updateData.append('itemStock', itemStock)
          updateData.append('itemId', itemId)
          updateData.append('categoryId', categoryId)
          updateData.append('token', token)

          if (itemImageInput.files.length > 0) {
            const inputImageItem = itemImageInput.files[0]
            updateData.append('inputImageItem', inputImageItem)
          }

          const response = await fetch('index.php?controller=item&action=edit', {
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
              const urlParams = new URLSearchParams(window.location.search)
              const groupId = urlParams.get('groupId')

              window.location.href = 'index.php?controller=category&groupId=' + groupId
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
          console.log(error)
          this.messageManager.errorMessage(error, 'messageContainer')
        }
      })
    }
  }
}
