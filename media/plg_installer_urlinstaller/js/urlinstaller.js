/**
* PLEASE DO NOT MODIFY THIS FILE. WORK ON THE ES6 VERSION.
* OTHERWISE YOUR CHANGES WILL BE REPLACED ON THE NEXT BUILD.
**/

/**
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
Joomla = window.Joomla || {};

(function (Joomla) {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    Joomla.submitbuttonurl = function () {
      var form = document.getElementById('adminForm'); // do field validation

      if (form.install_url.value === '' || form.install_url.value === 'http://' || form.install_url.value === 'https://') {
        Joomla.renderMessages({
          warning: [Joomla.Text._('PLG_INSTALLER_URLINSTALLER_NO_URL')]
        });
      } else {
        var loading = document.getElementById('loading');

        if (loading) {
          loading.classList.remove('hidden');
        }

        form.installtype.value = 'url';
        form.submit();
      }
    };
  });
})(Joomla);