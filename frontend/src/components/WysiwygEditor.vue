<template>
  <Editor
    v-model="innerContent"
    api-key="kzmeneek0tn5zoygp1hc0o1mtabrrdil492tstvu68jr7whh"
    :init="editorConfig"
  />
</template>

<script>
import Editor from '@tinymce/tinymce-vue'
import api from '@/api'

export default {
  name: 'WysiwygEditor',
  components: {
     Editor 
    },
  props: {
    modelValue: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      innerContent: this.modelValue,
      editorConfig: {
        height: 500,
        menubar: true,
        plugins: 'link code lists image',
        toolbar:
          'undo redo | formatselect | bold italic | alignleft aligncenter alignright | link image | bullist numlist | code',
        content_style: 'body { font-family:Arial, sans-serif; font-size:14px }',
        images_upload_handler: this.handleImageUpload,
        automatic_uploads: true
      }
    }
  },  methods: {
    handleImageUpload(blobInfo, progress) {
      return new Promise((resolve, reject) => {
        const formData = new FormData();
        formData.append('file', blobInfo.blob(), blobInfo.filename());
        
        api.post('/upload-image', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          },
          onUploadProgress: (e) => {
            progress(e.progress * 100);
          }
        })
        .then(response => {
          resolve(response.data.location);
        })
        .catch(error => {
          reject('Image upload failed: ' + error.message);
        });
      });
    }
  },
  watch: {
    modelValue(newVal) {
      if (newVal !== this.innerContent) {
        this.innerContent = newVal
      }
    },
    innerContent(newVal) {
      this.$emit('update:modelValue', newVal)
    }
  }
}
</script>