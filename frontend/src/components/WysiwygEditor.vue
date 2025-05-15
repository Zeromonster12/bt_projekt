<template>
  <Editor
    v-model="innerContent"
    api-key="kzmeneek0tn5zoygp1hc0o1mtabrrdil492tstvu68jr7whh"
    :init="editorConfig"
  />
</template>

<script>
import { ref, watch } from 'vue'
import Editor from '@tinymce/tinymce-vue'

export default {
  name: 'WysiwygEditor',
  components: { Editor },
  props: {
    modelValue: {
      type: String,
      default: ''
    }
  },
  setup(props, { emit }) {
    const innerContent = ref(props.modelValue)

    watch(() => props.modelValue, (newVal) => {
      if (newVal !== innerContent.value) {
        innerContent.value = newVal
      }
    })

    watch(innerContent, (newVal) => {
      emit('update:modelValue', newVal)
    })

    const editorConfig = {
      height: 500,
      menubar: true,
      plugins: 'link image code lists',
      toolbar:
        'undo redo | formatselect | bold italic | alignleft aligncenter alignright | link image | bullist numlist | code',
      content_style: 'body { font-family:Arial, sans-serif; font-size:14px }'
    }

    return { innerContent, editorConfig }
  }
}
</script>
