/** ========================================================================
 * Project     : compare-system-versions
 * Description : release
 * Author      : Adriano Rosa <https://adrianorosa.com>
 * Date        : 2020-07-23 17:16
 * ========================================================================
 * Copyright 2020 Adriano Rosa <https://adrianorosa.com>
 * ======================================================================== */

const util = require('util');
const exec = util.promisify(require('child_process').exec);
const releaseTask = require('release-task');
const { writeLog } = require('release-task/src/helpers');

// create a new instance of releaseTask
const tasks = new releaseTask({
  bumpFiles: ['composer.json', 'package.json'],
  commitFiles: ['composer.json', 'package.json', 'CHANGELOG.md'],
  config: {
    indentSize: 2
  }
});


const push = async () => {
  writeLog('TASK push');
  const {stdout, stderr} = exec('git push -u origin master --tags 2>&1');
  if (stderr) throw new Error(`TASK push result an error ${stderr}`);
  return `TASK push has been completed.\n\n${stdout}`;
};


// async function compilePHAR(version, options) {
const compilePHAR = async (version, options) => {
  writeLog('TASK compilePHAR');
  const { stdout, stderr } = await exec('box compile');

  if (stderr) {
    throw new Error(`An error occurred ${stderr}`);
  }

  if (stdout) {
    return `Compile phar using box DONE!\n\n${stdout}`
  }
}


// Create a custom task named push
tasks.addTask('push', push, {
  checked: false,
  value: 'push',
  name: 'Push new releases',
});

tasks.addTask('compilePHAR', compilePHAR, {
  checked: true,
  value: 'compilePHAR',
  name: 'Compile phar using box',
});

tasks.init();
