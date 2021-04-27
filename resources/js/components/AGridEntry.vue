<template>
  <li
      class="col-span-1 bg-white rounded-lg shadow">
    <div class="w-full flex items-center justify-between p-6 space-x-6">
      <div class="flex-1 truncate">
        <div class="flex items-center space-x-3">
          <h3 class="text-gray-900 text-sm leading-5 font-medium truncate">{{ test.method }}</h3>
        </div>
        <p class="mt-1 text-gray-500 text-sm leading-5 truncate">{{ test.class }}</p>
      </div>
      <div>
        <component :is="statusComponent"/>
      </div>
    </div>
    <div class="border-t border-gray-200">
      <div class="-mt-px flex">
        <div class="w-0 flex-1 justify-center flex border-r border-gray-200">
          <button
              @click="$emit('result', test.fullPath)"
              :class="{'opacity-0': test.lastResult === constants.TEST_STATUS_UNKNOWN}"
              class="group duration-150 ease-in-out focus:outline-none hover:text-gray-500 inline-flex items-center justify-center  text-gray-600 text-sm transition">
            <icon-info class="group-hover:text-indigo-700 transition duration-150 h-5 w-5 text-gray-500"/>
            <span class="ml-2">Result</span>
          </button>
        </div>

        <div class="-ml-px w-0 flex-1 flex">
          <button
              @click="$emit('run', test.fullPath)"
              class="relative w-0 flex-1 inline-flex items-center justify-center py-4 text-sm leading-5 text-gray-700 font-medium border border-transparent rounded-br-lg hover:text-gray-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 transition ease-in-out duration-150">
            <icon-play-solid class="w-5 h-5 text-indigo-600"/>
            <span class="ml-3">Run</span>
          </button>
        </div>
      </div>
    </div>
  </li>
</template>
<script>
import Utilities from "../utilities";
import TestStatusUnknown from "./TestStatusUnknown";
import TestStatusFailure from "./TestStatusFailure";
import TestStatusSuccess from "./TestStatusSuccess";
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
  computed: {
    statusComponent() {
      return "test-status-" + Utilities.getTestStatusComponent(this.test.lastResult);
    }
  }
}
</script>
