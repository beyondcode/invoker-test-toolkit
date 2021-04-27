<template>
  <tr class=" bg-white hover:bg-indigo-50 transition duration-700">
    <td class="px-6 py-4 whitespace-no-wrap items-center">
      <div class="flex items-center">
        <component :is="statusComponent"/>
        <div class="ml-4">
          {{ test.method }}
        </div>
      </div>
    </td>
    <td class="px-3  whitespace-no-wrap text-sm font-medium">
      <div class="flex items-center justify-end">
        <button
            @click="$emit('result', test.fullPath)"
            :class="{'opacity-0': test.lastResult === constants.TEST_STATUS_UNKNOWN}"
            class="group duration-150 ease-in-out focus:outline-none hover:text-gray-500 inline-flex items-center justify-center  text-gray-600 text-sm transition">
          <icon-info class="group-hover:text-indigo-700 transition duration-150 h-5 w-5 text-gray-500"/>
          <span class="ml-2">Result</span>
        </button>
      </div>
    </td>
    <td class="pl-2 pr-6 py-4 whitespace-no-wrap text-sm leading-5 font-medium">
      <div class="flex items-center justify-end">

        <button @click="$emit('run', test.fullPath)"
                class="group duration-150 ease-in-out focus:outline-none font-medium hover:text-gray-500 inline-flex items-center justify-center  text-gray-600 text-sm transition">
          <icon-play-solid class="group-hover:text-indigo-700 transition duration-150 w-5 h-5 text-indigo-600"/>
          <span class="ml-2">Run</span>
        </button>
      </div>

    </td>
  </tr>
</template>
<script>
import Utilities from "../utilities";
import TestStatusSuccess from "./TestStatusSuccess";
import TestStatusFailure from "./TestStatusFailure";
import TestStatusUnknown from "./TestStatusUnknown";
import TestStatusSkip from "./TestStatusSkip";
import IconPlaySolid from "./icons/IconPlaySolid";
import IconInfo from "./icons/IconInfo";
import Constants from "../constants";

export default {
  components: {IconInfo, IconPlaySolid, TestStatusUnknown, TestStatusFailure, TestStatusSuccess, TestStatusSkip},
  props: {
    test: Object
  },
  data() {
    return {
      constants: {
        TEST_STATUS_UNKNOWN: Constants.TEST_STATUS_UNKNOWN,
      }
    }
  },
  methods: {

  },
  computed: {
    statusComponent() {
      return "test-status-" + Utilities.getTestStatusComponent(this.test.lastResult);
    }
  }
}
</script>
