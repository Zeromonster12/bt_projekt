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
      editorInstance: null,
      editorConfig: {
        height: 500,
        menubar: true,
        plugins: 'link code lists image media',
        toolbar:
          'undo redo | formatselect | bold italic | alignleft aligncenter alignright | link image media file | bullist numlist | code',
        content_style: `
          body { font-family:Arial, sans-serif; font-size:14px }
          .file-link { 
            display: inline-flex; 
            align-items: center; 
            padding: 4px 8px; 
            background-color: #f5f5f5; 
            border: 1px solid #ddd; 
            border-radius: 4px; 
            color: #333; 
            text-decoration: none;
            margin: 2px 0;
          }
          .file-link::before { content: "📎"; margin-right: 6px; }
          .file-link:hover { background-color: #e5e5e5; text-decoration: underline; }
        `,
        images_upload_handler: this.handleImageUpload,
        automatic_uploads: true,
        file_picker_types: 'file image media',
        file_picker_callback: this.filePickerCallback,

        setup: function(editor) {
          this.editorInstance = editor;
          
          editor.ui.registry.addButton('file', {
            icon: 'document-properties',
            tooltip: 'Upload and Insert File',
            onAction: function() {
              editor.execCommand('mceMedia');
            }
          });
        }.bind(this)
      }
    }
  },
  methods: {
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
      });    },
    filePickerCallback(callback, value, meta) {
      const input = document.createElement('input');
      input.setAttribute('type', 'file');
      
      if (meta.filetype === 'image') {
        input.setAttribute('accept', 'image/*');
      } else if (meta.filetype === 'media') {
        input.setAttribute('accept', '.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.txt');
      } else {
        input.setAttribute('accept', '.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.txt');
      }
      
      input.onchange = function() {
        const file = input.files[0];
        if (!file) return;
        
        const formData = new FormData();
        formData.append('file', file);
        
        const self = this;
        
        api.post('/upload-file', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })
        .then(function(response) {
          const fileUrl = response.data.location;
          const fileName = file.name;
          const fileExt = fileName.split('.').pop().toLowerCase();
          
          const textTypes = ['txt', 'log', 'md', 'csv'];
          const docTypes = ['doc', 'docx', 'pdf', 'xls', 'xlsx', 'ppt', 'pptx', 'zip', 'rar'];
            if (textTypes.includes(fileExt) || docTypes.includes(fileExt)) {
            const html = `<a href="${fileUrl}" download="${fileName}" class="file-link">${fileName}</a>`;
            self.editorInstance.insertContent(html);
            callback('');
          }else if (meta.filetype === 'image') {
            callback(fileUrl, { alt: fileName });
          } else {
            callback(fileUrl, { title: fileName });
          }
        })
        .catch(function(error) {
          console.error('File upload failed:', error);
          alert('File upload failed: ' + (error.response?.data?.message || error.message));
        });
      }.bind(this);
      
      input.click();
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

<style>
.file-link {
  display: inline-flex;
  align-items: center;
  padding: 4px 8px;
  background-color: #f5f5f5;
  border: 1px solid #ddd;
  border-radius: 4px;
  color: #333;
  text-decoration: none;
  margin: 2px 0;
}

.file-link::before {
  content: "📎";
  margin-right: 6px;
}

.file-link:hover {
  background-color: #e5e5e5;
  text-decoration: underline;
}
</style>