import { CategoryManager } from './managers/category.js'
import { ItemManager } from './managers/item.js'
import { SliderManager } from './managers/slider.js'
import { AuthManager } from './managers/auth.js'
import { GroupManager } from './managers/group.js'
import { UserManager } from './managers/user.js'
import { ApiManager } from './managers/api.js'
import { MenuManager } from './managers/menu.js'

let groupToEditId
const groupManager = new GroupManager(groupToEditId)

groupManager.addGroup()
groupManager.deleteGroup()
groupManager.editGroup()
groupManager.updateGroup()

let categoryToEditId
const categoryManager = new CategoryManager(categoryToEditId)
categoryManager.addCategory()
categoryManager.deleteCategory()
categoryManager.editCategory()
categoryManager.updateCategory()
categoryManager.upRankCategory()
categoryManager.downRankCategory()
categoryManager.handlePageLoad()

const itemManager = new ItemManager()
itemManager.addItem()
itemManager.deleteItem()
itemManager.addImage()
itemManager.updateItem()

let sliderToEditId
const sliderManager = new SliderManager(sliderToEditId)
sliderManager.addImage()
sliderManager.addSlider()
sliderManager.deleteSlider()
sliderManager.editSlider()
sliderManager.updateSlider()
sliderManager.upRankSlider()
sliderManager.downRankSlider()

const authManager = new AuthManager()
authManager.login()

const userManager = new UserManager()
userManager.addUser()
userManager.deleteUser()

const apiManager = new ApiManager()
apiManager.addApiKeys()
apiManager.deleteApiKeys()
apiManager.editApiKeys()

const menuManager = new MenuManager()
menuManager.addMenu()
menuManager.deleteMenu()
