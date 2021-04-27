<template>
  <div
      class="bg-white flex flex-col items-center justify-center mx-auto px-6 xl:px-10 rounded shadow-md w-11/12 xl:w-4/5  ">


    <div v-show="testsBusy" class="flex justify-between w-full items-center mb-6 pt-8 ">
      <h3 class="text-lg leading-6 font-medium text-gray-900">
        Running tests...
      </h3>
      <icon-beaker class="animate-bounce text-indigo-600 h-6 w-6"/>
    </div>
    <div v-show="!testsBusy" class="flex justify-between w-full items-center mb-6 pt-8 ">
      <h3 class="text-lg leading-6 font-medium text-gray-900">
        Results
      </h3>
      <icon-beaker class="text-indigo-600 h-6 w-6"/>
    </div>

    <div
        class="inline-flex self-start opacity-0 transition duration-150 items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-indigo-100 text-indigo-800"
        :class="{'opacity-100': currentFilterLabel}">
      Results for {{ currentFilterLabel }}

      <button
          @click="clearFilters"
          type="button"
          class="flex-shrink-0 -mr-0.5 ml-1.5 inline-flex text-indigo-500 focus:outline-none focus:text-indigo-700"
          aria-label="Remove filters">
        <icon-x class="h-2 w-2"/>
      </button>
    </div>
    <div class="flex items-start justify-center w-full">

      <div class="flex flex-col flex-grow border-r border-gray-200 py-8 pb-4 bg-white overflow-y-auto  w-5/12">

        <div class="flex-grow flex flex-col">
          <ul class="testList bg-white space-y-1  h-72 xl:h-96 overflow-auto">
            <li v-for="test in testsToShow" :key="test.fullPath"
                @click="filterResultByTest(test)"
                :class="{'currentTest text-indigo-600 focus:outline-none border-indigo-600 focus:bg-indigo-100 bg-indigo-50': runningTest !== undefined && test.fullPath === runningTest.fullPath}"
                class="group flex items-center px-3 py-2 text-xs leading-5 text-gray-600 border-l-4 border-transparent hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:text-gray-900 focus:bg-gray-50 transition ease-in-out duration-150">
              {{ test.method }}
            </li>

          </ul>
        </div>

      </div>
      <div class=" w-7/12 pl-8 ">


        <ul class="testResults font-mono mt-4 mb-2 p-4 bg-indigo-900 text-white text-xs leading-7 rounded h-72 xl:h-96 overflow-auto">
          <li v-for="(result,index) in resultsToShow" class="pb-2">
            > {{ index }}
            <span class="block pl-4 pb-8">{{ result.output }} </span>
          </li>
        </ul>


      </div>
    </div>

    <ResultsBar :counters="counters" @filterBy="filterResultByStatus"/>

    <div class="flex py-2 xl:py-6 justify-end w-full">
      <a-button @click="closeTestRunner">Close</a-button>
    </div>

  </div>
</template>


<script>
import PluginContextStore from './../PluginContextStore';
import AButton from "./AButton";
import Constants from "../constants";
import ResultsBar from "./TestRunner/ResultsBar";
import IconBeaker from "./icons/IconBeaker";
import IconX from "./icons/IconX";
import Utilities from "../utilities";

export default {
  components: {IconX, IconBeaker, ResultsBar, AButton},
  props: {
    tests: [],
    testsRunning: false
  },
  data() {
    return {
      testsBusy: true,
      runningTest: {},
      results: {},
      resultsToShow: {},
      testsToShow: [],
      currentFilterLabel: null,

      counters: {
        total: 0,
        succeeded: 0,
        skipped: 0,
        failed: 0
      },

      sidebarScrollTop: 0,

      constants: {
        TEST_STATUS_ALL: Constants.TEST_STATUS_ALL,
        TEST_STATUS_OK: Constants.TEST_STATUS_OK,
        TEST_STATUS_SKIP: Constants.TEST_STATUS_SKIP,
        TEST_STATUS_FAIL: Constants.TEST_STATUS_FAIL,
      }
    }
  },
  mounted() {
  },
  methods: {
    async runTests() {
      this.sidebarScrollTop = 0;
      this.testsToShow = this.tests;

      this.testsBusy = true;

      try {
        for (let test of this.tests) {
          if (this.testsRunning === false) return;
          await this.runSingleTest(test);
        }

      } catch (err) {
        let message = "Error while executing test.";

        if (typeof err === "object") {
          message = err.userMessage;
          console.error(err.additionalInformation);
        }

        console.error(message);
        invoker.showNotification({title: message, type: "error"});
      }

      this.testsBusy = false;

    },


    async runSingleTest(test) {

      this.runningTest = test;

      let testResult = "";

      let response = await PluginContextStore.plugin.executePhar([
        'run-single-test',
        invoker.getProject().path,
        Utilities.getBinary(),
        Utilities.getPHPUnitExecutable(),
        test.fullPath
      ]);

      testResult = JSON.parse(response);

      if (testResult.error !== undefined && testResult.error === true) {
        throw {userMessage: testResult.userMessage, additionalInformation: testResult.additionalInformation};
      }

      this.results[test.fullPath] = testResult;

      this.writeTestCache(test.fullPath, testResult);

      this.clearFilters();

      this.counters.total++;

      if (testResult.status === "OK") {
        this.counters.succeeded++;
      } else if (testResult.status === "FAIL") {
        this.counters.failed++;
      } else if (testResult.status === "SKIP") {
        this.counters.skipped++;
      } else {
        // todo internal error
      }

    },

    async writeTestCache(testFullPath, testResult) {
      window.mainStorage.delete('invokerTestToolkit.results.' + testFullPath);
      window.mainStorage.set('invokerTestToolkit.results.' + testFullPath, testResult);
    }
    ,

    filterResultByTest(selectedTest) {
      if (this.testsBusy) return;
      this.resultsToShow = {};
      this.testsToShow = this.tests;


      for (let testClass in this.results) {
        if (testClass === selectedTest.fullPath) {
          this.resultsToShow[testClass] = this.results[testClass];
        }
      }

      this.runningTest = selectedTest;
      this.currentFilterLabel = selectedTest.method;

    }
    ,

    filterResultByStatus(selectedStatus) {
      if (this.testsBusy) return;
      this.resultsToShow = {};
      this.testsToShow = [];

      if (selectedStatus === this.constants.TEST_STATUS_ALL) {
        this.resultsToShow = this.results;
        this.testsToShow = this.tests;
      } else {
        for (let testClass in this.results) {
          let testResult = this.results[testClass];
          if (testResult.status === selectedStatus) {
            this.resultsToShow[testClass] = testResult;
            this.testsToShow.push(this.tests.filter((test) => {
              return (test.fullPath === testClass);
            })[0]);
          }
        }
      }

      this.currentFilterLabel =
          (selectedStatus === this.constants.TEST_STATUS_OK ? "succeeded" :
                  (selectedStatus === this.constants.TEST_STATUS_SKIP ? "skipped, risky or incomplete" :
                      (selectedStatus === this.constants.TEST_STATUS_FAIL ? "failed" : "internal failed"))
          ) + " tests";
      this.runningTest = {};

    }
    ,

    clearFilters() {
      this.currentFilterLabel = null;
      this.resultsToShow = this.results;
      this.testsToShow = this.tests;
    }
    ,

    closeTestRunner() {
      this.testsBusy = true;
      this.testsRunning = true;
      this.runningTest = {};
      this.results = {};
      this.resultsToShow = {};

      this.counters.total = this.counters.succeeded = this.counters.skipped = this.counters.failed = this.sidebarScrollTop = 0;
      this.$emit("close");

    }
  },


  watch: {
    tests: function (newTests, oldTests) {
      this.runningTest = this.tests[0];
    },
    runningTest: function (newTestRunning, oldTestRunning) {
      // toDo Can't be used for more than one element on a page because of a chromium bug - @see https://stackoverflow.com/a/63563437
      // const currentActive = this.$el.getElementsByClassName('currentTest')[0];
      // currentActive.scrollIntoView({behavior: 'smooth'});

      // scroll the sidebar
      this.sidebarScrollTop += 40;
      const testList = this.$el.getElementsByClassName('testList')[0];

      if (this.sidebarScrollTop >= 120 && (this.sidebarScrollTop % 120) === 0) {
        testList.scrollTo({top: this.sidebarScrollTop, behavior: 'smooth'});
      }

      // scroll the results
      const lastResult = this.$el.getElementsByClassName('testResults')[0];
      lastResult.scrollTo({top: lastResult.scrollHeight, behavior: 'smooth'});
    },
    testsRunning: function (newTestsRunning, oldTestsRunning) {
      if (newTestsRunning === true)
        this.runTests();
    },
  }


}
</script>
