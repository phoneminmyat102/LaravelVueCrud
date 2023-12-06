<template>
    <div class="ck-editor__editable">
      <CKEditor :editor="ClassicEditor" v-model="editorData" :config="editorConfig"/>
    </div>
  </template>

  <script>
  import { ref, watch } from 'vue';
  import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
  import { component as CKEditor} from '@ckeditor/ckeditor5-vue';

  export default {
    components: {
        CKEditor,
    },
    props: {
      modelValue: String,
    },
    data() {
      return {
        ClassicEditor,
        editorData: ref(this.modelValue || ''),
        editorConfig: {
          toolbar: {
            items: [
              'heading',
              '|',
              'bold',
              'italic',
              'link',
              'bulletedList',
              'numberedList',
              'blockQuote',
              'undo',
              'redo'
            ]
          },
        },
      };
    },
    watch: {
        modelValue(newValue) {
            this.editorData = newValue;
        },
        editorData(newValue) {
            this.$emit('update:modelValue', newValue);
        },
    },
  };
  </script>

  <style>
 .ck.ck-editor__main .ck-editor__editable {
    min-height: 200px;
  }
  </style>
