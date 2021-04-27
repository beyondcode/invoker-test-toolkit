<template>

  <router-link
      v-if="projectOpened"
      title="Tests"
      :to="{ name: 'simple-tests-index' }"
      active-class="bg-invoker-purple bg-opacity-25 text-invoker-purple"
      class="mt-2 group flex items-center px-2 py-2 text-sm leading-5 font-medium rounded-md text-gray-500 hover:text-invoker-purple hover:bg-invoker-purple hover:bg-opacity-25 focus:outline-none transition ease-in-out duration-150"
  >
    <icon-beaker class="h-5 w-5"/>
  </router-link>
</template>
<script>
import IconBeaker from "./icons/IconBeaker";

export default {
  components: {IconBeaker},
  computed: {
    // You can use this property to only show the plugin, when a project is opened
    projectOpened() {
      return !!this.$store.state.project.id;
    }
  }
};
</script>