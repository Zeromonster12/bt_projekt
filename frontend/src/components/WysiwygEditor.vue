<template>
  <div>
    <!-- Toolbar -->
    <div class="toolbar mb-3">
      <button @click="toggleBold" class="btn btn-sm btn-secondary">Bold</button>
      <button @click="toggleItalic" class="btn btn-sm btn-secondary">Italic</button>
      <button @click="setHeading(1)" class="btn btn-sm btn-secondary">H1</button>
      <button @click="setHeading(2)" class="btn btn-sm btn-secondary">H2</button>
      <button @click="toggleBulletList" class="btn btn-sm btn-secondary">Bullet List</button>
      <button @click="toggleOrderedList" class="btn btn-sm btn-secondary">Ordered List</button>
      <button @click="addImage" class="btn btn-sm btn-secondary">Add Image</button>
      <button @click="toggleHtmlView" class="btn btn-sm btn-secondary">Toggle HTML</button>
    </div>

    <!-- Editor -->
    <EditorContent v-if="editor && !showHtml" :editor="editor" class="editor-content" />

    <!-- HTML View -->
    <pre v-if="showHtml" class="html-view">{{ editor.getHTML() }}</pre>
  </div>
</template>

<script>
import { Editor, EditorContent } from "@tiptap/vue-3";
import StarterKit from "@tiptap/starter-kit";
import Image from "@tiptap/extension-image";
import axios from "axios";

export default {
  name: "WysiwygEditor",
  components: {
    EditorContent,
  },
  props: {
    modelValue: String,
  },
  emits: ["update:modelValue"],
  data() {
    return {
      editor: null,
      showHtml: false,
    };
  },
  mounted() {
    this.editor = new Editor({
      extensions: [StarterKit, Image],
      content: this.modelValue,
      onUpdate: ({ editor }) => {
        this.$emit("update:modelValue", editor.getHTML());
      },
    });

    // Drag and drop support
    this.$nextTick(() => {
      const editorElement = this.$el.querySelector(".editor-content");
      editorElement.addEventListener("drop", this.handleDrop);
    });
  },
  methods: {
    toggleBold() {
      this.editor?.chain().focus().toggleBold().run();
    },
    toggleItalic() {
      this.editor?.chain().focus().toggleItalic().run();
    },
    setHeading(level) {
      this.editor?.chain().focus().setHeading({ level }).run();
    },
    toggleBulletList() {
      this.editor?.chain().focus().toggleBulletList().run();
    },
    toggleOrderedList() {
      this.editor?.chain().focus().toggleOrderedList().run();
    },
    addImage() {
      const input = document.createElement("input");
      input.type = "file";
      input.accept = "image/*";

      input.addEventListener("change", async () => {
        const file = input.files[0];
        if (file) {
          await this.uploadImage(file);
        }
      });

      input.click();
    },
    async uploadImage(file) {
      const formData = new FormData();
      formData.append("image", file);

      try {
        const response = await axios.post(
          "http://localhost:8000/api/upload-image",
          formData,
          {
            headers: {
              "Content-Type": "multipart/form-data",
              Authorization: `Bearer ${localStorage.getItem("authToken")}`, // Replace with your token logic
            },
            withCredentials: true,
          }
        );

        const imageUrl = response.data.url;
        console.log("Image uploaded successfully:", imageUrl);

        if (this.editor) {
          this.editor.commands.setImage({ src: imageUrl });
        }
      } catch (error) {
        if (error.response && error.response.status === 401) {
          alert("You are not authorized to upload images. Please log in.");
        } else {
          console.error("Error uploading image:", error);
          alert("Failed to upload image.");
        }
      }
    },
    handleDrop(event) {
      event.preventDefault();
      const file = event.dataTransfer.files[0];

      if (file && file.type.startsWith("image/")) {
        this.uploadImage(file);
      }
    },
    toggleHtmlView() {
      this.showHtml = !this.showHtml;
    },
  },
  watch: {
    modelValue(newValue) {
      if (this.editor && newValue !== this.editor.getHTML()) {
        this.editor.commands.setContent(newValue);
      }
    },
  },
  beforeUnmount() {
    if (this.editor) {
      this.editor.destroy();
    }
  },
};
</script>

<style scoped>
.editor-content {
  min-height: 300px;
  font-family: Arial, sans-serif;
  background-color: #f9f9f9;
  padding: 15px;
  border: 1px solid #ddd;
  border-radius: 5px;
  overflow-y: auto;
}
.toolbar button {
  margin-right: 5px;
}
.html-view {
  background-color: #f4f4f4;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
  white-space: pre-wrap;
}
</style>