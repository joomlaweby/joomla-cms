/**
* PLEASE DO NOT MODIFY THIS FILE. WORK ON THE ES6 VERSION.
* OTHERWISE YOUR CHANGES WILL BE REPLACED ON THE NEXT BUILD.
**/

/**
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
Joomla = window.Joomla || {};

(Joomla => {
  'use strict';

  document.addEventListener('DOMContentLoaded', () => {
    Joomla.submitbuttonfolder = () => {
      const form = document.getElementById('adminForm'); // do field validation

      if (form.install_directory.value === '') {
        Joomla.renderMessages({
          warning: [Joomla.Text._('PLG_INSTALLER_FOLDERINSTALLER_NO_INSTALL_PATH')]
        });
      } else {
        const loading = document.getElementById('loading');

        if (loading) {
          loading.classList.remove('hidden');
        }

        form.installtype.value = 'folder';
        form.submit();
      }
    };
  });
})(Joomla);