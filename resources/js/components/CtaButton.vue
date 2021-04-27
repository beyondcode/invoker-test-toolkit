<template>
  <button @click="$emit('click', $event)"  type="button" :class="padding" class="transition duration-150 hover:shadow-md relative inline-flex items-center border border-transparent text-sm leading-5 font-medium inline-flex justify-center items-center rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:shadow-outline-indigo focus:border-indigo-700 active:bg-indigo-700">
    <slot></slot>
  </button>
</template>
<script>
export default {
  props: {
    padding: {
      type: String,
      required: false,
      default: "px-4 py-2"
    }
  }
}
</script>
