# Atomic Design - Frontend

Este projeto segue o padrão **Atomic Design** para organização de componentes Vue.

## Estrutura de Pastas

```
frontend/src/components/
├── atoms/          # Componentes base (inputs, botões, labels)
├── molecules/      # Combinação de atoms (form groups, cards)
├── organisms/      # Componentes complexos (formulários completos)
├── templates/      # Layouts e templates de páginas
└── index.js        # Registro global de componentes
```

## Padrão de Nomenclatura

Os componentes são registrados globalmente com prefixos que indicam seu nível:

- **Atoms**: `At` + NomeComponente (ex: `AtButton`, `AtLabel`)
- **Molecules**: `Mol` + NomeComponente (ex: `MolFormGroup`, `MolCard`)
- **Organisms**: `Org` + NomeComponente (ex: `OrgLoginForm`)
- **Templates**: `Tpl` + NomeComponente (ex: `TplLoginTemplate`)

## Como Usar

### Importação Global (Automática)

Todos os componentes são registrados globalmente no `main.js`. Você pode usá-los diretamente em qualquer template sem precisar importar:

```vue
<template>
  <TplLoginTemplate />
</template>

<script setup>
</script>
```

### Exemplo: Crear um Novo Componente Atômico

1. **Criar o arquivo** em `src/components/atoms/MyInput.vue`:

```vue
<template>
  <input
    :type="type"
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)"
  />
</template>

<script setup>
defineProps({
  type: String,
  modelValue: String
})

defineEmits(['update:modelValue'])
</script>
```

2. **Adicionar ao registro** em `src/components/index.js`:

```javascript
import MyInput from './atoms/MyInput.vue'

export function registerGlobalComponents(app) {
  // ... outros componentes
  app.component('AtMyInput', MyInput)
}
```

### Exemplo: Criar uma Molécula

1. **Criar o arquivo** em `src/components/molecules/MyFormGroup.vue`:

```vue
<template>
  <div>
    <AtLabel :html-for="id">{{ label }}</AtLabel>
    <AtTextInput
      :id="id"
      :modelValue="modelValue"
      @update:modelValue="$emit('update:modelValue', $event)"
    />
  </div>
</template>

<script setup>
defineProps({
  id: String,
  label: String,
  modelValue: String
})

defineEmits(['update:modelValue'])
</script>
```

2. **Registrar globalmente** e usar em qualquer lugar.

### Exemplo: Criar um Organismo

```vue
<template>
  <form @submit.prevent="handleSubmit">
    <MolEmailInput v-model="email" />
    <MolPasswordInput v-model="password" />
    <AtButton type="submit">Login</AtButton>
  </form>
</template>

<script setup>
import { ref } from 'vue'

const email = ref('')
const password = ref('')

const handleSubmit = () => {
  // Lógica de submit
}
</script>
```

### Exemplo: Usar um Template em uma View

```vue
<!-- pages/Auth/Login.vue -->
<template>
  <TplLoginTemplate />
</template>

<script setup>
</script>
```

## Boas Práticas

1. **Atoms**: Componentes simples e reutilizáveis, sem lógica complexa
2. **Molecules**: Combinações de atoms com pouca lógica, focados em apresentação
3. **Organisms**: Componentes complexos com lógica de negócio
4. **Templates**: Layouts de páginas, combinam organisms e molecules
5. **Pages/Views**: Importam e utilizam templates

## Tipagem e Props

Sempre defina corretamente os props e emits em cada componente:

```vue
<script setup>
defineProps({
  variant: {
    type: String,
    default: 'primary',
    validator: (value) => ['primary', 'secondary'].includes(value)
  }
})

defineEmits(['click', 'change'])
</script>
```

## Estilos

Use Tailwind CSS para estilos, mantendo consistência com as classes já definidas.

## Adicionar Novos Componentes

Ao adicionar um novo componente:

1. Crie o arquivo `.vue` na pasta apropriada
2. Importe no `src/components/index.js`
3. Registre com `app.component()` usando o padrão de nomenclatura
4. Use livremente em qualquer template do projeto

Não é necessário importar o componente em cada arquivo - ele está disponível globalmente!
