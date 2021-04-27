<template>
  <div
      class="bg-white flex flex-col items-center justify-center mx-auto px-6 xl:px-10 rounded shadow-md w-11/12 xl:w-4/5  ">
    <div class="flex justify-between w-full items-center mb-6 pt-8 ">
      <h3 class="text-lg leading-6 font-medium text-gray-900">
        Last Result for <span class="text-indigo-600">{{ test.fullPath }}</span>
      </h3>
      <component :is="statusComponent"/>
    </div>
    <ul class="w-full mx-8 font-mono mt-4 mb-2 p-4 bg-indigo-900 text-white text-xs leading-7 rounded h-72 xl:h-96 overflow-auto">
      {{ test.lastResultOutput }}
    </ul>


    <div class="flex pt-2 pb-4 xl:pb-6 justify-end w-full">
      <a-button @click="close">Close</a-button>
    </div>

  </div>


</template>


<script>
import TestStatusSuccess from "../TestStatusSuccess";
import TestStatusUnknown from "../TestStatusUnknown";
import TestStatusFailure from "../TestStatusFailure";
import TestStatusSkip from "../TestStatusSkip";
import AButton from "../AButton";
import Utilities from "../../utilities";

export default {
  components: {AButton, TestStatusSkip, TestStatusFailure, TestStatusUnknown, TestStatusSuccess},
  props: {
    test: {}
  },

  methods: {
    close() {
      this.$emit('close');
    }
  },
  computed: {
    statusComponent() {
      return "test-status-" + Utilities.getTestStatusComponent(this.test.lastResult);
    }
  }
}
</script>
