export class MessageManager {
  messageVisible = false

  successMessage(data, blocId) {
    if (!this.messageVisible) {
      const container = document.getElementById(blocId)

      if (container) {
        const successMessageElement = document.createElement('div')
        successMessageElement.innerHTML = data.successMessage
        container.parentNode.insertBefore(successMessageElement, container)

        this.messageVisible = true

        setTimeout(() => {
          successMessageElement.remove()
          this.messageVisible = false
        }, 1200)
      }
    }
  }

  refreshMessage(data, blocId) {
    window.scrollTo(0, 0)
    const refreshMessageElement = document.createElement('div')
    refreshMessageElement.innerHTML = data.refreshMessage
    const container = document.getElementById(blocId)
    container.parentNode.insertBefore(refreshMessageElement, container)

    setTimeout(() => {
      window.location.href = 'index.php?controller=auth&action=logout'
    }, 30000)
  }

  failedMessage(data, blocId) {
    if (!this.messageVisible) {
      const failedMessageElement = document.createElement('div')
      failedMessageElement.innerHTML = data.error
      const container = document.getElementById(blocId)
      container.parentNode.insertBefore(failedMessageElement, container)

      this.messageVisible = true

      setTimeout(() => {
        failedMessageElement.remove()
        this.messageVisible = false
      }, 3000)
    }
  }

  errorMessage(error, blocId) {
    if (!this.messageVisible) {
      const errorContainer = document.createElement('div')
      errorContainer.classList.add(
        'alert',
        'alert-danger',
        'fixed-top',
        'w-100',
        'p-3',
        'shadow-sm'
      )
      errorContainer.style.top = '0'
      errorContainer.setAttribute('role', 'alert')

      const closeContainer = document.createElement('div')
      closeContainer.classList.add('text-end')

      const closeButton = document.createElement('button')
      closeButton.setAttribute('type', 'button')
      closeButton.classList.add('btn-close', 'text-center')
      closeButton.setAttribute('data-bs-dismiss', 'alert')
      closeButton.setAttribute('aria-label', 'Close')

      closeContainer.appendChild(closeButton)

      const textContainer = document.createElement('p')
      textContainer.classList.add('text-center')
      textContainer.textContent = error

      errorContainer.appendChild(closeContainer)
      errorContainer.appendChild(textContainer)

      const container = document.getElementById(blocId)
      container.parentNode.insertBefore(errorContainer, container)

      this.messageVisible = true

      setTimeout(() => {
        errorContainer.remove()
        this.messageVisible = false
      }, 3000)
    }
  }
}
