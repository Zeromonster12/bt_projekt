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

export default {
  name: "WysiwygEditor",
  props: {
    modelValue: String,
  },
  emits: ["update:modelValue"],
  components: {
    EditorContent,  // Registrácia komponentu EditorContent
  },
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
  },
  methods: {
    toggleBold() {
      if (this.editor) {
        this.editor.chain().focus().toggleBold().run();
      }
    },
    toggleItalic() {
      if (this.editor) {
        this.editor.chain().focus().toggleItalic().run();
      }
    },
    setHeading(level) {
      if (this.editor) {
        this.editor.chain().focus().setHeading({ level }).run();
      }
    },
    toggleBulletList() {
      if (this.editor) {
        this.editor.chain().focus().toggleBulletList().run();
      }
    },
    toggleOrderedList() {
      if (this.editor) {
        this.editor.chain().focus().toggleOrderedList().run();
      }
    },
    addImage() {
      const url = prompt("Enter image URL");
      if (url && this.editor) {
        this.editor.chain().focus().setImage({ src: url }).run();
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
