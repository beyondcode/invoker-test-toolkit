<template>
  <button @click="$emit('click', $event)" type="button" :class="padding" class="transition duration-150 focus:shadow-outline-indigo hover:shadow-md inline-flex items-center border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
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
