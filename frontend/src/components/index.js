// Atoms
import Label from './atoms/Label.vue'
import TextInput from './atoms/TextInput.vue'
import Button from './atoms/Button.vue'
import Checkbox from './atoms/Checkbox.vue'
import Link from './atoms/Link.vue'
import Logo from './atoms/Logo.vue'
import Tooltip from './atoms/Tooltip.vue'
import ThemeToggle from './atoms/ThemeToggle.vue'

// Molecules
import FormGroup from './molecules/FormGroup.vue'
import EmailInput from './molecules/EmailInput.vue'
import PasswordInput from './molecules/PasswordInput.vue'
import CheckboxLabel from './molecules/CheckboxLabel.vue'
import FormFooter from './molecules/FormFooter.vue'
import Card from './molecules/Card.vue'
import FormHelper from './molecules/FormHelper.vue'
import CompanyNameInput from './molecules/CompanyNameInput.vue'
import CompanyEmailInput from './molecules/CompanyEmailInput.vue'
import CnpjInput from './molecules/CnpjInput.vue'
import AdminNameInput from './molecules/AdminNameInput.vue'
import AdminEmailInput from './molecules/AdminEmailInput.vue'

// Organisms
import LoginForm from './organisms/LoginForm.vue'
import RegisterForm from './organisms/RegisterForm.vue'

// Templates
import LoginTemplate from './templates/LoginTemplate.vue'
import AuthLayout from './templates/AuthLayout.vue'
import RegisterTemplate from './templates/RegisterTemplate.vue'

// Existing components
import HelloWorld from './HelloWorld.vue'

export function registerGlobalComponents(app) {
  // Atoms
  app.component('AtLabel', Label)
  app.component('AtTextInput', TextInput)
  app.component('AtButton', Button)
  app.component('AtCheckbox', Checkbox)
  app.component('AtLink', Link)
  app.component('AtLogo', Logo)
  app.component('AtTooltip', Tooltip)

  app.component('AtThemeToggle', ThemeToggle)

  // Molecules
  app.component('MolFormGroup', FormGroup)
  app.component('MolEmailInput', EmailInput)
  app.component('MolPasswordInput', PasswordInput)
  app.component('MolCheckboxLabel', CheckboxLabel)
  app.component('MolFormFooter', FormFooter)
  app.component('MolCard', Card)
  app.component('MolFormHelper', FormHelper)
  app.component('MolCompanyNameInput', CompanyNameInput)
  app.component('MolCompanyEmailInput', CompanyEmailInput)
  app.component('MolCnpjInput', CnpjInput)
  app.component('MolAdminNameInput', AdminNameInput)
  app.component('MolAdminEmailInput', AdminEmailInput)

  // Organisms
  app.component('OrgLoginForm', LoginForm)
  app.component('OrgRegisterForm', RegisterForm)

  // Templates
  app.component('TplLoginTemplate', LoginTemplate)
  app.component('TplAuthLayout', AuthLayout)
  app.component('TplRegisterTemplate', RegisterTemplate)

  // Existing
  app.component('HelloWorld', HelloWorld)
}
