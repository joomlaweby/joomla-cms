/**
* PLEASE DO NOT MODIFY THIS FILE. WORK ON THE ES6 VERSION.
* OTHERWISE YOUR CHANGES WILL BE REPLACED ON THE NEXT BUILD.
**/

/**
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    var loading = document.getElementById('loading');
    var installer = document.getElementById('installer-install');

    if (loading && installer) {
      loading.style.top = parseInt(installer.offsetTop - window.pageYOffset, 10);
      loading.style.left = 0;
      loading.style.width = '100%';
      loading.style.height = '100%';
      loading.classList.add('hidden');
      loading.style.marginTop = '-10px';
    }
  });
})();