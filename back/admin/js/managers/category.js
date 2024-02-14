import { MessageManager } from '../utils/MessageManager.js'
import { ItemManager } from './item.js'

export class CategoryManager {
  constructor(categoryToEditId) {
    this.categoryToEditId = categoryToEditId
    this.itemManager = new ItemManager()
    this.messageManager = new MessageManager()

    this.categoryTitleInput = document.getElementById('categoryTitle')
    this.categoryDescriptionInput = document.getElementById('categoryDescription')
    this.categoryRankInput = document.getElementById('categoryRank')
    this.groupIdInput = document.getElementById('groupId')
    this.tokenInput = document.getElementById('token')
    this.jwt = localStorage.getItem('jwtToken')
  }

  handlePageLoad() {
    const successMessage = localStorage.getItem('successMessage')
    if (successMessage) {
      this.messageManager.successMessage(JSON.parse(successMessage), 'displayCategory')
      localStorage.removeItem('successMessage')
    }
  }

  addCategory() {
    const addButton = document.getElementById('addCategory')
    if (addButton) {
      addButton.addEventListener('click', async () => {
        try {
          const categoryTitle = this.categoryTitleInput.value
          const categoryDescription = this.categoryDescriptionInput.value
          const categoryRank = this.categoryRankInput.value
          const token = this.tokenInput.value
          const groupId = this.groupIdInput.value
          const jwt = this.jwt

          if (!categoryTitle || !categoryDescription) {
            const message = 'Veuillez remplir tous les champs.'
            throw new Error(message)
          }

          if (!token || !jwt) {
            const message = "Problème d'authentification"
            throw new Error(message)
          }

          const addData = new FormData()
          addData.append('categoryTitle', categoryTitle)
          addData.append('categoryDescription', categoryDescription)
          addData.append('categoryRank', categoryRank)
          addData.append('groupId', groupId)
          addData.append('token', token)

          const response = await fetch('index.php?controller=category&action=add', {
            method: 'POST',
            body: addData,
            headers: {
              Authorization: `Bearer ${jwt}`,
            },
          })
          const data = await response.json()

          if (response.status === 200) {
            // let categoryRankValue = parseInt(data.categoryRank) + 1
            // this.categoryRankInput.value = categoryRankValue

            // this.categoryTitleInput.value = ''
            // this.categoryDescriptionInput.value = ''

            // const categoryBlock = data.categoryBlock
            // const categoryList = data.categoryList

            // document
            //   .getElementById('displayCategory')
            //   .insertAdjacentHTML('beforeend', categoryBlock)
            // document
            //   .getElementById('displayBulletPointCategory')
            //   .insertAdjacentHTML('beforeend', categoryList)

            // this.deleteCategory()
            // this.editCategory()
            // this.updateCategory()
            // this.upRankCategory()
            // this.downRankCategory()

            // this.itemManager.deleteItem()
            // this.itemManager.addItem()
            // this.itemManager.addImage()
            // this.itemManager.updateItem()

            this.messageManager.successMessage(data, 'messageContainer')
            localStorage.setItem('successMessage', JSON.stringify(data))
            location.reload()
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

  deleteCategory() {
    const buttons = document.querySelectorAll('.deleteCategory')
    for (const btn of buttons) {
      btn.removeEventListener('click', this.deleteCategoryListener)
      btn.addEventListener('click', this.deleteCategoryListener)
    }
  }

  deleteCategoryListener = async (event) => {
    try {
      const btn = event.currentTarget
      const categoryId = btn.dataset.id
      const token = this.tokenInput.value
      const jwt = this.jwt

      if (!token || !jwt) {
        const message = "Problème d'authentification"
        throw new Error(message)
      }

      const deleteData = new FormData()
      deleteData.append('categoryId', categoryId)
      deleteData.append('token', token)

      const response = await fetch('index.php?controller=category&action=delete', {
        method: 'POST',
        body: deleteData,
        headers: {
          Authorization: `Bearer ${jwt}`,
        },
      })

      const data = await response.json()

      if (response.status === 200) {
        let categoryRankValue = parseInt(this.categoryRankInput.value)
        categoryRankValue = categoryRankValue - 1
        this.categoryRankInput.value = categoryRankValue

        const selectRank = document.getElementById('inputRank' + data.categoryId)
        if (selectRank) {
          const allRank = document.querySelectorAll('.inputRank')
          for (let i = 0; i < allRank.length; i++) {
            if (allRank[i].value > selectRank.value) {
              allRank[i].value = allRank[i].value - 1
            }
          }
        }

        document.getElementById('pushCategory' + data.categoryId).remove()
        document.getElementById('category' + data.categoryId).remove()

        this.categoryTitleInput.value = ''
        this.categoryDescriptionInput.value = ''

        document.getElementById('addCategory').style.display = 'block'
        document.getElementById('updateCategory').style.display = 'none'

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

  editCategory() {
    const buttons = document.querySelectorAll('.editCategory')
    for (const btn of buttons) {
      btn.addEventListener('click', () => {
        this.categoryToEditId = btn.dataset.id

        const divCategory = document.getElementById('pushCategory' + this.categoryToEditId)
        const categoryTitle = divCategory.querySelector('h4')
        const categoryDescription = divCategory.querySelector('p')

        this.categoryTitleInput.value = categoryTitle.textContent
        this.categoryDescriptionInput.value = categoryDescription.textContent
        document.getElementById('actionCategory').textContent = 'Modifier'

        document.getElementById('addCategory').style.display = 'none'
        document.getElementById('updateCategory').style.display = 'block'
      })
    }
  }

  updateCategory() {
    const updateCategory = document.getElementById('updateCategory')
    if (updateCategory) {
      updateCategory.removeEventListener('click', this.updateCategoryListener)
      updateCategory.addEventListener('click', this.updateCategoryListener)
    }
  }

  updateCategoryListener = async () => {
    try {
      const categoryId = this.categoryToEditId

      const categoryTitle = this.categoryTitleInput.value
      const categoryDescription = this.categoryDescriptionInput.value
      const token = this.tokenInput.value
      const jwt = this.jwt

      if (!categoryTitle || !categoryDescription) {
        const message = 'Veuillez remplir tous les champs.'
        throw new Error(message)
      }

      if (!token || !jwt) {
        const message = "Problème d'authentification"
        throw new Error(message)
      }

      const updateData = new FormData()
      updateData.append('categoryTitle', categoryTitle)
      updateData.append('categoryDescription', categoryDescription)
      updateData.append('categoryId', categoryId)
      updateData.append('token', token)

      const response = await fetch('index.php?controller=category&action=edit', {
        method: 'POST',
        body: updateData,
        headers: {
          Authorization: `Bearer ${jwt}`,
        },
      })

      const data = await response.json()

      if (response.status === 200) {
        document.getElementById('actionCategory').textContent = 'Ajouter'
        this.categoryTitleInput.value = ''
        this.categoryDescriptionInput.value = ''
        document.getElementById('addCategory').style.display = 'block'
        document.getElementById('updateCategory').style.display = 'none'
        document.getElementById('title' + data.categoryId).textContent = data.categoryTitle
        document.getElementById('desc' + data.categoryId).textContent = data.categoryDescription
        document.getElementById('category' + data.categoryId).textContent = data.categoryTitle

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

  upRankCategory() {
    const buttons = document.querySelectorAll('.downRank')
    for (const btn of buttons) {
      btn.removeEventListener('click', this.upRankListener)
      btn.addEventListener('click', () => this.upRankListener(btn))
    }
  }

  downRankCategory() {
    const buttons = document.querySelectorAll('.upRank')
    for (const btn of buttons) {
      btn.removeEventListener('click', this.downRankListener)
      btn.addEventListener('click', () => this.downRankListener(btn))
    }
  }

  upRankListener = async (btn) => {
    try {
      const thisCategoryId = btn.dataset.id
      const categoryRankInput = document.getElementById('inputRank' + thisCategoryId)
      const thisCategoryBloc = document.getElementById('pushCategory' + thisCategoryId)
      const token = this.tokenInput.value
      const jwt = this.jwt

      if (!token || !jwt) {
        const message = "Problème d'authentification"
        throw new Error(message)
      }

      let thisCategoryRankValue = parseInt(categoryRankInput.value)

      let prevBloc
      let prevCategoryBlocId
      let previousCategoryRankValue

      prevBloc = thisCategoryBloc.previousElementSibling

      if (!prevBloc) {
        const message = 'Opération impossible : Aucun élément précédent trouvé.'
        throw new Error(message)
      }

      prevCategoryBlocId = prevBloc.dataset.id
      previousCategoryRankValue = parseInt(prevBloc.querySelector('.inputRank')?.value || 0)
      previousCategoryRankValue++
      thisCategoryRankValue--

      const upRankData = new FormData()
      upRankData.append('thisCategoryRankValue', thisCategoryRankValue)
      upRankData.append('thisCategoryId', thisCategoryId)
      upRankData.append('previousCategoryRankValue', previousCategoryRankValue)
      upRankData.append('prevCategoryBlocId', prevCategoryBlocId)
      upRankData.append('token', token)

      const response = await fetch('index.php?controller=category&action=upRank', {
        method: 'POST',
        body: upRankData,
        headers: {
          Authorization: `Bearer ${jwt}`,
        },
      })

      const data = await response.json()

      if (response.status === 200) {
        document.getElementById('inputRank' + data.thisCategoryId).value =
          data.thisCategoryRankValue
        const thisCategoryBloc = document.getElementById('pushCategory' + data.thisCategoryId)
        const thisCategoryBullet = document.getElementById('category' + data.thisCategoryId)

        const prevCategoryBloc = thisCategoryBloc.previousElementSibling

        const previousCategoryRankInput = prevCategoryBloc.querySelector('.inputRank')

        previousCategoryRankInput.value = parseInt(previousCategoryRankInput.value) + 1

        thisCategoryBloc.parentNode.insertBefore(prevCategoryBloc, thisCategoryBloc.nextSibling)

        const prevCategoryBullet = thisCategoryBullet.previousElementSibling

        thisCategoryBullet.parentNode.insertBefore(
          prevCategoryBullet,
          thisCategoryBullet.nextSibling
        )
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

  downRankListener = async (btn) => {
    try {
      const thisCategoryId = btn.dataset.id
      const categoryRankInput = document.getElementById('inputRank' + thisCategoryId)
      const thisCategoryBloc = document.getElementById('pushCategory' + thisCategoryId)
      const token = this.tokenInput.value
      const jwt = this.jwt

      if (!token || !jwt) {
        const message = "Problème d'authentification"
        throw new Error(message)
      }

      let thisCategoryRankValue = parseInt(categoryRankInput.value)

      let nextBloc
      let nextCategoryBlocId
      let nextCategoryRankValue

      nextBloc = thisCategoryBloc.nextElementSibling
      if (!nextBloc) {
        const message = 'Opération impossible : Aucun élément suivant trouvé.'
        throw new Error(message)
      }

      nextCategoryBlocId = nextBloc.dataset.id
      nextCategoryRankValue = parseInt(nextBloc.querySelector('.inputRank').value)
      nextCategoryRankValue--
      thisCategoryRankValue++

      const downRankData = new FormData()
      downRankData.append('thisCategoryRankValue', thisCategoryRankValue)
      downRankData.append('thisCategoryId', thisCategoryId)
      downRankData.append('nextCategoryRankValue', nextCategoryRankValue)
      downRankData.append('nextCategoryBlocId', nextCategoryBlocId)
      downRankData.append('token', token)

      const response = await fetch('index.php?controller=category&action=downRank', {
        method: 'POST',
        body: downRankData,
        headers: {
          Authorization: `Bearer ${jwt}`,
        },
      })

      const data = await response.json()

      if (response.status === 200) {
        document.getElementById('inputRank' + data.thisCategoryId).value =
          data.thisCategoryRankValue

        const thisCategoryBloc = document.getElementById('pushCategory' + data.thisCategoryId)
        const thisCategoryBullet = document.getElementById('category' + data.thisCategoryId)
        const parentCategory = document.getElementById('displayCategory')
        const parentBulletCategory = document.getElementById('displayBulletPointCategory')

        const nextCategoryBloc = thisCategoryBloc.nextElementSibling
        const nextCategoryBullet = thisCategoryBullet.nextElementSibling

        const nextCategoryRankInput = nextCategoryBloc.querySelector('.inputRank')

        nextCategoryRankInput.value = parseInt(nextCategoryRankInput.value) - 1

        parentCategory.insertBefore(nextCategoryBloc, thisCategoryBloc)

        parentBulletCategory.insertBefore(nextCategoryBullet, thisCategoryBullet)
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
}
