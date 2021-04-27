<template>
  <div class="w-full px-4 pt-8">
    <h3 class="text-lg leading-6 font-medium text-gray-900">
      Executed {{ counters.total }} tests.
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-3">
      <div class="bg-white border-r border-gray-200 py-4 xl:py-8">
        <div class="px-10">
          <div class="flex items-center">
            <TestStatusSuccess/>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm leading-5 font-medium text-gray-500 truncate">
                  Succeeded
                </dt>
                <dd class="flex items-baseline">
                  <div class="text-2xl leading-8 font-semibold text-gray-900">
                    {{ counters.succeeded }}
                  </div>
                  <div class="ml-2 flex items-baseline text-sm leading-5 font-semibold text-green-600">
                    {{ percentCounters.succeeded }} %
                  </div>
                </dd>
              </dl>

            </div>
            <a v-show="counters.succeeded > 0" @click="filterBy(constants.TEST_STATUS_OK)"
               class="cursor-pointer font-medium text-indigo-600 hover:text-indigo-500 transition ease-in-out duration-150">
              <icon-filter class="h-6 w-6"/>
            </a>
          </div>
        </div>
      </div>

      <div class="bg-white border-r border-gray-200 py-4 xl:py-8">
        <div class="px-7">
          <div class="flex items-center">
            <TestStatusSkip/>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm leading-5 font-medium text-gray-500 truncate">
                  Skipped, incomplete or risky
                </dt>
                <dd class="flex items-baseline">
                  <div class="text-2xl leading-8 font-semibold text-gray-900">
                    {{ counters.skipped }}
                  </div>
                  <div v-show="counters.skipped > 0"
                       class="ml-2 flex items-baseline text-sm leading-5 font-semibold text-orange-600">
                    {{ percentCounters.skipped }} %
                  </div>
                </dd>
              </dl>

            </div>
            <a
                v-show="counters.skipped > 0"
                @click="filterBy(constants.TEST_STATUS_SKIP)"
                class="cursor-pointer font-medium text-indigo-600 hover:text-indigo-500 transition ease-in-out duration-150">
              <icon-filter class="h-6 w-6"/>
            </a>
          </div>
        </div>
      </div>

      <div class="bg-white border-r border-transparent py-4 xl:py-8">
        <div class="px-7">
          <div class="flex items-center">
            <TestStatusFailure/>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm leading-5 font-medium text-gray-500 truncate">
                  Failed
                </dt>
                <dd class="flex items-baseline">
                  <div class="text-2xl leading-8 font-semibold text-gray-900">
                    {{ counters.failed }}
                  </div>
                  <div v-show="counters.failed > 0"
                       class="ml-2 flex items-baseline text-sm leading-5 font-semibold text-red-600">
                    {{ percentCounters.failed }} %
                  </div>
                </dd>
              </dl>

            </div>
            <a v-show="counters.failed > 0" @click="filterBy(constants.TEST_STATUS_FAIL)"
               class="cursor-pointer font-medium text-indigo-600 hover:text-indigo-500 transition ease-in-out duration-150">
              <icon-filter class="h-6 w-6"/>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>


</template>


<script>
import Constants from "../../constants";
import TestStatusSuccess from "../TestStatusSuccess";
import TestStatusUnknown from "../TestStatusUnknown";
import TestStatusFailure from "../TestStatusFailure";
import TestStatusSkip from "../TestStatusSkip";
import IconFilter from "../icons/IconFilter";

export default {
  components: {IconFilter, TestStatusSkip, TestStatusFailure, TestStatusUnknown, TestStatusSuccess},
  props: {
    counters: {}
  },
  data() {
    return {
      constants: {
        TEST_STATUS_ALL: Constants.TEST_STATUS_ALL,
        TEST_STATUS_OK: Constants.TEST_STATUS_OK,
        TEST_STATUS_SKIP: Constants.TEST_STATUS_SKIP,
        TEST_STATUS_FAIL: Constants.TEST_STATUS_FAIL,
      },

    }
  },
  methods: {
    filterBy(testStatus) {
      this.$emit("filterBy", testStatus)
    }
  },
  computed: {
    percentCounters() {
      return {
        succeeded: Math.round((this.counters.succeeded / this.counters.total) * 100),
        skipped: Math.round((this.counters.skipped / this.counters.total) * 100),
        failed: Math.round((this.counters.failed / this.counters.total) * 100)
      }
    }
  }
}
</script>
