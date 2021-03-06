<?php
/**
 * HUBzero CMS
 *
 * Copyright 2005-2015 HUBzero Foundation, LLC.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * HUBzero is a registered trademark of Purdue University.
 *
 * @package   hubzero-cms
 * @copyright Copyright 2005-2015 HUBzero Foundation, LLC.
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace Components\Storefront\Admin\Controllers;

require_once dirname(__DIR__) . DS . 'helpers' . DS . 'restrictions.php';

use Hubzero\Component\AdminController;
use Components\Storefront\Models\Sku;
use Components\Storefront\Admin\Helpers\RestrictionsHelper;
use Request;
use Route;
use User;
use App;

/**
 * Controller class
 */
class Whitelist extends AdminController
{
	/**
	 * Display a list of all users
	 *
	 * @return  void
	 */
	public function displayTask()
	{
		// Get SKU ID
		$sId = Request::getInt('id');
		if (empty($sId))
		{
			$sId = Request::getInt('sId', 0);
		}
		$this->view->sId = $sId;

		// Get SKU
		$sku = Sku::getInstance($sId);
		$this->view->sku = $sku;

		// Get filters
		$this->view->filters = array(
			// Get sorting variables
			'sort' => Request::getState(
				$this->_option . '.' . $this->_controller . '.sort',
				'filter_order',
				'uId'
			),
			'sort_Dir' => Request::getState(
				$this->_option . '.' . $this->_controller . '.sortdir',
				'filter_order_Dir',
				'ASC'
			),
			// Get paging variables
			'limit' => Request::getState(
				$this->_option . '.' . $this->_controller . '.limit',
				'limit',
				Config::get('list_limit'),
				'int'
			),
			'start' => Request::getState(
				$this->_option . '.' . $this->_controller . '.limitstart',
				'limitstart',
				0,
				'int'
			)
		);

		// Get record count
		$this->view->filters['return'] = 'count';
		$this->view->total = RestrictionsHelper::getWhitelistedSkuUsers($this->view->filters, $sId);

		// Get records
		$this->view->filters['return'] = 'list';
		$this->view->rows = RestrictionsHelper::getWhitelistedSkuUsers($this->view->filters, $sId);

		// Output the HTML
		$this->view->display();
	}

	/**
	 * Cancel a task (redirects to default task)
	 *
	 * @return  void
	 */
	public function cancelTask()
	{
		// Set the redirect
		App::redirect(
			Route::url('index.php?option=' . $this->_option . '&controller=skus&task=edit&id=' . Request::getInt('sId', 0), false)
		);
	}

	/**
	 * Remove an entry
	 *
	 * @return  void
	 */
	public function removeTask()
	{
		// Check for request forgeries
		Request::checkToken();

		// Incoming
		$ids = Request::getArray('id', array());
		$sId = Request::getInt('sId');

		RestrictionsHelper::removeUsers($ids);

		$msg = "User(s) deleted";
		$type = 'message';

		App::redirect(
			Route::url('index.php?option=' . $this->_option . '&controller=' . $this->_controller . '&task=dispaly&id=' . $sId, false),
			$msg,
			$type
		);
	}

	/**
	 * Display a form for a new entry
	 *
	 * @return  void
	 */
	public function newTask()
	{
		$sId = Request::getInt('id', 0);
		$this->view->sId = $sId;

		// Set any errors
		foreach ($this->getErrors() as $error)
		{
			$this->view->setError($error);
		}

		// Output the HTML
		$this->view->display();
	}

	/**
	 * Add users
	 *
	 * @return  void
	 */
	public function addusersTask()
	{
		$sId = Request::getInt('sId', 0);
		$users = Request::getString('users', '');

		$users = explode(',', $users);
		$noHubUserMatch = array();
		$matched = 0;
		foreach ($users as $user)
		{
			$user = trim($user);

			$usr = User::getInstance($user);
			$uId = $usr->get('id', 0);
			if ($uId)
			{
				RestrictionsHelper::addWhitelistedSkuUser($uId, $sId);
				$matched++;
			}
			else
			{
				// Are we adding by username?
				if ($user && is_string($user))
				{
					RestrictionsHelper::addWhitelistedSkuUser($uId, $sId, $user);
				}
				else
				{
					$noHubUserMatch[] = $user;
				}
			}
		}

		$this->view->matched = $matched;
		$this->view->noUserMatch = $noHubUserMatch;
		$this->view->display();
	}
}
