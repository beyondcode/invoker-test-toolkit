<template>

  <div class="overflow-auto w-full">

    <modal-background v-show="testsLoading">
      <div class="bg-white flex flex-col items-center justify-center mx-auto p-12 rounded shadow-md w-1/3">
        <icon-beaker class="animate-bounce text-indigo-600 h-8 w-8 mb-4"/>
        <span class="font-medium text-xs text-gray-700">Initializing Test Suite ...</span>
      </div>
    </modal-background>

    <modal-background v-show="testsRunning">
      <test-runner :tests="selectedTests" :testsRunning="testsRunning" @close="closeTestRunner"/>
    </modal-background>

    <modal-background v-show="showGroupsPanel"/>

    <modal-background v-show="showTestResult">
      <results-modal :test="testForResultModal" @close="closeTestResult"/>
    </modal-background>

    <div class="bg-white px-4 py-5 border-b border-gray-200 sm:px-6 w-full">
      <div class="-ml-4 -mt-4 flex justify-between items-center flex-wrap sm:flex-no-wrap">
        <div class="ml-4 mt-4">
          <h3 class="text-lg leading-6 font-medium text-gray-900">
            Invoker Test Toolkit
          </h3>
          <p class="mt-1 text-sm leading-5 text-gray-500">
            Detect, run and evaluate your applications PHPUnit tests in Invoker.
          </p>
        </div>
        <div class="ml-4 mt-4 flex-shrink-0">
          <div class="inline-flex">
            <a-button class="mr-4" @click="initializeSuite">
              <icon-refresh class="h-4 w-4"/>
            </a-button>
            <a-button class="mr-4" @click="clearTestCache">
              Clear Cache
            </a-button>
            <cta-button @click="runAll" type="button">
              <icon-play-solid class="h-5 w-5 mr-3"/>
              Run all
            </cta-button>
          </div>
        </div>
      </div>
    </div>


    <div class="rounded-md bg-blue-50 m-8 p-4" v-if="tests.length <= 0 && testsLoading === false">
      <div class="flex">
        <div class="flex-shrink-0">
          <icon-info class="h-5 w-5 text-blue-500"/>
        </div>
        <div class="ml-3 flex-1 md:flex md:justify-between">
          <p class="text-sm leading-5 text-blue-700">
            No PHPUnit tests detected.
          </p>
          <div>
          </div>
        </div>
      </div>
    </div>

    <div v-else>


      <section class="absolute z-20 inset-y-0 left-14 max-w-full flex">

        <transition name="slidePanel" enter-class="transform transition ease-in-out duration-500 sm:duration-700"
                    enter-active-class="translate-x-full" enter-to-class="translate-x-0"
                    leave-class="transform transition ease-in-out duration-500 sm:duration-700"
                    leave-active-class="translate-x-0" leave-to-class="translate-x-full">

          <div v-show="showGroupsPanel" class="w-screen max-w-md">
            <div class="h-full flex flex-col space-y-6 py-6 bg-white shadow-xl overflow-y-scroll">
              <header class="px-4 sm:px-6">
                <div class="flex items-start justify-between space-x-3">
                  <h2 class="text-lg leading-7 font-medium text-gray-900">
                    Available test classes
                  </h2>
                  <div class="h-7 flex items-center">
                    <button @click="showGroupsPanel = false" aria-label="Close panel"
                            class="text-gray-400 hover:text-gray-500 transition ease-in-out duration-150">
                      <icon-x class="h-6 w-6"></icon-x>
                    </button>
                  </div>
                </div>
              </header>
              <div class="relative flex-1 px-4 sm:px-6">
                <nav class="flex-1 px-2 bg-white space-y-1">
                  <a href="#"
                     @click="selectGroup(null)"
                     class="border-gray-200 duration-150 ease-in-out flex focus:outline-none font-medium hover:border-gray-500 items-center leading-5 px-2 py-5 text-gray-900 text-sm transition">
                    <span :class="{'text-indigo-500': selectedGroup === undefined || selectedGroup === null}">
                      Show all
                    </span>
                  </a>
                  <a v-for="group in groups" href="#"
                     @click="selectGroup(group.fullPath)"
                     class="border-gray-200 duration-150 ease-in-out flex focus:outline-none font-medium hover:border-gray-500 items-center leading-5 px-2 py-5 text-gray-900 text-sm transition">
                <span
                    :class="{'text-indigo-500': selectedGroup === group.fullPath}"
                    class="text-gray-800 font-light">{{ group.namespace }}</span>
                    <span
                        :class="{'text-indigo-600': selectedGroup === group.fullPath}"
                        class="text-gray-900 font-medium">{{ group.class }}</span>

                  </a>
                </nav>
              </div>
            </div>
          </div>
        </transition>
      </section>

      <div class="bg-white px-4 py-5 border-b border-gray-200">
        <div class="flex justify-between items-center flex-wrap sm:flex-no-wrap">
          <div class="flex items-center">

            <cta-button class="mr-3" padding="p-1" @click="showGroupsPanel = true">
              <icon-filter-solid class="h-4 w-4"/>
            </cta-button>
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                  <span v-if="selectedGroup === null">
                    All tests
                  </span>
              <span v-else>
                    Tests in
                    <span class="text-indigo-500 font-light">
                      {{ selectedGroupObject != null ? selectedGroupObject.namespace : "" }}</span><!----><span
                  class="text-indigo-600 font-medium">{{ selectedGroupObject != null ? selectedGroupObject.class : "" }}
                    </span>
                  </span>
            </h3>
          </div>
          <div class="ml-4 flex-shrink-0 flex items-center">

            <a-button v-show="selectedGroup !== null" @click="runGroup(selectedGroupObject)" type="button">
              <icon-play-solid class="h-5 w-5 mr-3"/>
              Run Class
            </a-button>

            <div class="pl-8">
              <a-button @click="listView = 'list'">
                <icon-list class="h-5 w-5 hover:text-indigo-400"
                           :class="{'text-indigo-600': listView === 'list'}"/>
              </a-button>
              <a-button @click="listView = 'grid'">
                <icon-grid class="h-5 w-5 hover:text-indigo-400"
                           :class="{'text-indigo-600': listView === 'grid'}"/>
              </a-button>
            </div>


          </div>
        </div>
      </div>

      <ul v-if="listView ==='grid'"
          class="p-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5">


        <li is="a-grid-entry"
            v-for="(test, index) in testsToShow"
            v-bind:key="test.method + test.lastResult"
            v-bind:test="test"
            v-bind:index="index"
            @run="runSingleTest"
            @result="showLastTestResult(test.fullPath)">

        </li>


      </ul>

      <div v-if="listView === 'list'" class="flex items-stretch mt-6">

        <div class="w-full">

          <div class="overflow-hidden border-b border-gray-100">

            <table class="min-w-full divide-y divide-gray-100">
              <tbody class="bg-white divide-y divide-gray-100">

              <tr is="a-list-entry"
                  v-for="(test, index) in testsToShow"
                  v-bind:key="test.method + test.lastResult"
                  v-bind:test="test"
                  v-bind:index="index"
                  @run="runSingleTest(test.fullPath)"
                  @result="showLastTestResult(test.fullPath)">

              </tr>

              </tbody>

            </table>

          </div>
        </div>

      </div>
    </div>

  </div>


</template>
<script lang="ts">
import {ipcRenderer} from 'electron';
import PluginContextStore from './../PluginContextStore';
import CtaButton from "./CtaButton.vue";
import AButton from "./AButton.vue";
import AGridEntry from "./AGridEntry.vue";
import AListEntry from "./AListEntry.vue";
import ModalBackground from "./ModalBackground.vue";
import TestRunner from "./TestRunner.vue";
import IconX from "./icons/IconX.vue";
import IconFilter from "./icons/IconFilter.vue";
import IconFilterSolid from "./icons/IconFilterSolid.vue";
import IconPlaySolid from "./icons/IconPlaySolid.vue";
import Constants from "../constants";
import ResultsModal from "./TestRunner/ResultsModal.vue";
import IconBeaker from "./icons/IconBeaker.vue";
import IconRefresh from "./icons/IconRefresh.vue";
import IconInfo from "./icons/IconInfo.vue";
import IconList from "./icons/IconList.vue";
import IconGrid from "./icons/IconGrid.vue";
import Utilities from "../utilities";


export default {
  components: {
    IconGrid,
    IconList,
    IconInfo,
    IconRefresh,
    IconBeaker,
    ResultsModal,
    IconPlaySolid,
    IconFilterSolid,
    IconFilter, IconX, TestRunner, ModalBackground, AListEntry, AGridEntry, AButton, CtaButton
  },
  data() {
    return {
      detectedTests: {},
      tests: [],
      testsToShow: [],
      groups: [],
      testsLoading: false,
      testsRunning: false,
      listView: "list",
      selectedGroup: null,
      showGroupsPanel: false,
      preselectedTests: [],
      showTestResult: false,
      testForResultModal: {},
      constants: {
        TEST_STATUS_ALL: Constants.TEST_STATUS_ALL,
        TEST_STATUS_OK: Constants.TEST_STATUS_OK,
        TEST_STATUS_SKIP: Constants.TEST_STATUS_SKIP,
        TEST_STATUS_FAIL: Constants.TEST_STATUS_FAIL,
        TEST_STATUS_UNKNOWN: Constants.TEST_STATUS_UNKNOWN,
      }
    }

  },
  mounted() {
    this.initializeSuite();
  },
  watch: {
    selectedGroup: function (newGroup, oldGroup) {
      if (newGroup !== oldGroup)
        this.filterTestsToShow();
    }
  },
  methods: {
    selectGroup(group) {
      this.selectedGroup = group;
      this.showGroupsPanel = false;
    },
    filterTestsToShow() {
      if (this.selectedGroup === null) {
        this.testsToShow = this.tests;
        return;
      }

      this.testsToShow = this.tests.filter((test) => {
        return (test.class === this.selectedGroup);
      });

    },
    async runAll() {
      try {
        this.preselectedTests = this.tests;
        this.testsRunning = true;
      } catch (err) {
        console.log(err);
        invoker.showNotification({title: "An error occured.", type: "error"});
      }
    },
    async runGroup(group) {
      this.preselectedTests = this.tests.filter((test) => {
        return (test.class === group.fullPath);
      });

      this.testsRunning = true;
    },

    async runSingleTest(selectedTest) {
      this.preselectedTests = this.getSingleTest(selectedTest);

      this.testsRunning = true;
    },

    getSingleTest(testPath) {
      return this.tests.filter((test) => {
        return (test.fullPath === testPath);
      });
    },

    async closeTestRunner() {
      this.preselectedTests = [];
      this.testsRunning = false;

      await this.initializeSuite();
    },

    async initializeSuite() {
      try {

        this.testsLoading = true;
        this.tests = this.testsToShow = this.groups = [];

        this.detectedTests = await this.loadTests();

        this.tests = JSON.parse(this.detectedTests.tests);
        this.groups = JSON.parse(this.detectedTests.groups);

        await this.readLastTestResults();

        this.selectGroup(null);
        this.filterTestsToShow();

        this.testsLoading = false;

      } catch (err) {

        let message = "Error while initializing test suite.";

        if (typeof err === "object") {
          message = err.userMessage;
          console.error(err.additionalInformation);
        }

        console.error(message);

        this.tests = this.groups = [];
        this.testsLoading = false;

        invoker.showNotification({title: message, type: "error"});
      }
    },


    async loadTests(e) {

      let response = await PluginContextStore.plugin.executePhar([
        'list-all-tests',
        invoker.getProject().path,
        Utilities.getBinary(),
        Utilities.getPHPUnitExecutable()
      ]);

      let result = JSON.parse(response);

      if (result.error !== undefined && result.error === true) {
        throw {userMessage: result.userMessage, additionalInformation: result.additionalInformation};
      } else {
        return result;
      }
    },

    async readLastTestResults() {
      // todo check validity with PHPUnit cache (if available)


      for (let test of this.tests) {

        // try to read last result data from cache
        let lastResultsCache = (window as any).mainStorage.get('invokerTestToolkit.results.' + test.fullPath);

        // todo trycatch
        let lastResultStatus;
        let lastResultsOutput;
        if (lastResultsCache === undefined) {
          lastResultStatus = this.constants.TEST_STATUS_UNKNOWN
        } else {
          lastResultStatus = lastResultsCache.status;
          lastResultsOutput = lastResultsCache.output;
        }

        test["lastResult"] = lastResultStatus;
        test["lastResultOutput"] = lastResultsOutput;
      }

    },

    async clearTestCache() {
      await (window as any).mainStorage.delete('invokerTestToolkit.results');
      await this.initializeSuite();
    },


    showLastTestResult(path) {
      this.showTestResult = true;
      this.testForResultModal = this.getSingleTest(path)[0];
    },

    closeTestResult() {
      this.showTestResult = false;
      this.testForResultModal = {};
    }


  },
  computed: {
    selectedGroupObject() {
      return this.groups.find((group) => {
        return (group.fullPath === this.selectedGroup);
      });
    },
    selectedTests() {
      if (this.preselectedTests == [])
        return this.tests;
      else return this.preselectedTests;
    }
  }
}
</script>