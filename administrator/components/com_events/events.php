<?php
/**
 * HUBzero CMS
 *
 * Copyright 2005-2011 Purdue University. All rights reserved.
 *
 * This file is part of: The HUBzero(R) Platform for Scientific Collaboration
 *
 * The HUBzero(R) Platform for Scientific Collaboration (HUBzero) is free
 * software: you can redistribute it and/or modify it under the terms of
 * the GNU Lesser General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or (at your option) any
 * later version.
 *
 * HUBzero is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * HUBzero is a registered trademark of Purdue University.
 *
 * @package   hubzero-cms
 * @author    Shawn Rice <zooley@purdue.edu>
 * @copyright Copyright 2005-2011 Purdue University. All rights reserved.
 * @license   http://www.gnu.org/licenses/lgpl-3.0.html LGPLv3
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

$option = JRequest::getCmd('option');

if (!JFactory::getUser()->authorise('core.manage', $option))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

require_once(JPATH_COMPONENT_SITE . DS . 'helpers' . DS . 'tags.php');
require_once(JPATH_COMPONENT_SITE . DS . 'helpers' . DS . 'date.php');
require_once(JPATH_COMPONENT_SITE . DS . 'tables' . DS . 'category.php');
require_once(JPATH_COMPONENT_SITE . DS . 'tables' . DS . 'event.php');
require_once(JPATH_COMPONENT_SITE . DS . 'tables' . DS . 'config.php');
require_once(JPATH_COMPONENT_SITE . DS . 'tables' . DS . 'page.php');
require_once(JPATH_COMPONENT_SITE . DS . 'tables' . DS . 'respondent.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'html.php');

$controllerName = JRequest::getCmd('controller', 'events');
if (!file_exists(JPATH_COMPONENT_ADMINISTRATOR . DS . 'controllers' . DS . $controllerName . '.php'))
{
	$controllerName = 'events';
}

JSubMenuHelper::addEntry(
	JText::_('COM_EVENTS'),
	'index.php?option=com_events&controller=events',
	$controllerName == 'events'
);
JSubMenuHelper::addEntry(
	JText::_('COM_EVENTS_CATEGORIES'),
	'index.php?option=com_events&controller=categories',
	$controllerName == 'categories'
);
JSubMenuHelper::addEntry(
	JText::_('COM_EVENTS_CONFIGURATION'),
	'index.php?option=com_events&controller=configure',
	$controllerName == 'configure'
);

require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'controllers' . DS . $controllerName . '.php');
$controllerName = 'EventsController' . ucfirst($controllerName);

// Instantiate controller
$controller = new $controllerName();
$controller->execute();
$controller->redirect();
