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
    Joomla.submitbuttonfolder = function () {
      var form = document.getElementById('adminForm'); // do field validation

      if (form.install_directory.value === '') {
        Joomla.renderMessages({
          warning: [Joomla.Text._('PLG_INSTALLER_FOLDERINSTALLER_NO_INSTALL_PATH')]
        });
      } else {
        var loading = document.getElementById('loading');

        if (loading) {
          loading.classList.remove('hidden');
        }

        form.installtype.value = 'folder';
        form.submit();
      }
    };
  });
})(Joomla);