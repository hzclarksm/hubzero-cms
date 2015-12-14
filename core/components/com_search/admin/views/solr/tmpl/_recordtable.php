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

// No direct access.
defined('_HZEXEC_') or die();

// Generate table headers
// @TODO create a standard document model, create view object
$fields = array();
foreach ($this->documents as $document)
{
	foreach ($document->getFields() as $field => $value)
	{
		array_push($fields, $field);
	}
	break;
}
?>
<style>
#noresults {
	margin-right: auto;
	margin-left: auto;
}
</style>

<?php if (count($this->documents) > 0): ?>
<table class="adminlist searchDocument">
	<thead>
		<tr>
		<?php foreach ($fields as $field): ?>
			<th><?php echo $field; ?></th>
		<?php endforeach; ?>
		<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($this->documents as $document): ?>
			<tr>
			<?php foreach ($document as $field => $value): ?>
				<td><?php echo (!is_array($value) ? $value : ''); ?>
				<?php 
					if (is_array($value))
					{
						$x = 0;
						foreach ($value as $v)
						{
							if (isset($value[$x + 1]))
							{
								echo $v . '/';
							}
							else
							{
								echo $v;
							}
						}
					}
					?>
					<?php 
						// Get row's ID
						if ($field == 'id')
						{
							$searchID = $value;
						}
						if ($field == 'hubid')
						{
							$hubID = $value;
						}
					?>
				</td>
			<?php endforeach; ?>
				<td>
					<a href="#" class="button">Update</a>
					<a href="<?php echo Route::url('index.php?&option='.$this->option.'&controller='.$this->controller.'&task=deleteDocument&docID='.$searchID.'&type='.$this->type.'&hubid='.$hubID); ?>" class="button danger">Remove</a>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php else: ?>
<div id="noresults" class="warning message"><?php echo Lang::txt('COM_SEARCH_NO_RESULTS_FOUND'); ?></div>
<?php endif; ?>
