import {TEST_STATUS_FAIL, TEST_STATUS_OK, TEST_STATUS_SKIP, TEST_STATUS_UNKNOWN} from "./constants";

export default class Utilities {
    static getTestStatusComponent(statusCode: string) {

        switch (statusCode) {

            case TEST_STATUS_OK:
                return 'success';
            case TEST_STATUS_FAIL:
                return 'failure';
            case TEST_STATUS_SKIP:
                return 'skip';
            case TEST_STATUS_UNKNOWN:
            default:
                return 'unknown';

        }

    }

    static getBinary() {

        let phpBinary;

        let project = invoker.getProject();
        if (project.is_local) {
            phpBinary = invoker.phpBinary;
        } else {
            phpBinary = invoker.remotePhpBinary;
        }

        return phpBinary;
    }

    static getPHPUnitExecutable() {
        let executable = "/vendor/bin/phpunit";
        if (process.platform === "win32") executable = "/vendor/phpunit/phpunit/phpunit";

        return executable;
    }

    static getRandomInt(min, max) {
        min = Math.ceil(min);
        max = Math.floor(max);
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }
}
