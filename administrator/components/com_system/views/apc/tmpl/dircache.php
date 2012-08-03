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
 * @copyright Copyright 2005-2011 Purdue University. All rights reserved.
 * @license   http://www.gnu.org/licenses/lgpl-3.0.html LGPLv3
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Menu items
JToolBarHelper::title(JText::_('APC Directory Entries'), 'config.png');

$this->MYREQUEST = $this->MYREQUEST;
$MY_SELF   = $this->MY_SELF;

?>

<div role="navigation" class="sub-navigation">
	<ul id="subsubmenu">
		<li><a href="index.php?option=<?php echo $this->option; ?>&amp;controller=<?php echo $this->controller; ?>">Host</a></li> 
		<li><a href="index.php?option=<?php echo $this->option; ?>&amp;controller=<?php echo $this->controller; ?>&amp;task=system">System</a></li>
		<li><a href="index.php?option=<?php echo $this->option; ?>&amp;controller=<?php echo $this->controller; ?>&amp;task=user">User</a></li> 
		<li><a href="index.php?option=<?php echo $this->option; ?>&amp;controller=<?php echo $this->controller; ?>&amp;task=dircache" class="active">Directory</a></li>
		<li><a href="index.php?option=<?php echo $this->option; ?>&amp;controller=<?php echo $this->controller; ?>&amp;task=version">Version</a></li>
	</ul>
</div>

<div class="content">
	<div class="sorting">
		<form method="post" action="index.php">
			Scope:
			<input type="hidden" name="OB" value="<?php echo $this->MYREQUEST['OB']; ?>" />
			<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
			<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
			<input type="hidden" name="task" value="<?php echo $this->task; ?>" />
			<select name="SCOPE">

<?php
	echo 
		"<option value=A",$this->MYREQUEST['SCOPE']=='A' ? " selected":"",">Active</option>",
		"<option value=D",$this->MYREQUEST['SCOPE']=='D' ? " selected":"",">Deleted</option>",
		"</select>",
		", Sorting:<select name=SORT1>",
		"<option value=H",$this->MYREQUEST['SORT1']=='H' ? " selected":"",">Total Hits</option>",
		"<option value=Z",$this->MYREQUEST['SORT1']=='Z' ? " selected":"",">Total Size</option>",
		"<option value=T",$this->MYREQUEST['SORT1']=='T' ? " selected":"",">Number of Files</option>",
		"<option value=S",$this->MYREQUEST['SORT1']=='S' ? " selected":"",">Directory Name</option>",
		"<option value=A",$this->MYREQUEST['SORT1']=='A' ? " selected":"",">Avg. Size</option>",
		"<option value=C",$this->MYREQUEST['SORT1']=='C' ? " selected":"",">Avg. Hits</option>",
		'</select>',
		'<select name=SORT2>',
		'<option value=D',$this->MYREQUEST['SORT2']=='D' ? ' selected':'','>DESC</option>',
		'<option value=A',$this->MYREQUEST['SORT2']=='A' ? ' selected':'','>ASC</option>',
		'</select>',
		'<select name=COUNT onChange="form.submit()">',
		'<option value=10 ',$this->MYREQUEST['COUNT']=='10' ? ' selected':'','>Top 10</option>',
		'<option value=20 ',$this->MYREQUEST['COUNT']=='20' ? ' selected':'','>Top 20</option>',
		'<option value=50 ',$this->MYREQUEST['COUNT']=='50' ? ' selected':'','>Top 50</option>',
		'<option value=100',$this->MYREQUEST['COUNT']=='100'? ' selected':'','>Top 100</option>',
		'<option value=150',$this->MYREQUEST['COUNT']=='150'? ' selected':'','>Top 150</option>',
		'<option value=200',$this->MYREQUEST['COUNT']=='200'? ' selected':'','>Top 200</option>',
		'<option value=500',$this->MYREQUEST['COUNT']=='500'? ' selected':'','>Top 500</option>',
		'<option value=0  ',$this->MYREQUEST['COUNT']=='0'  ? ' selected':'','>All</option>',
		'</select>',
		", Group By Dir Level:<select name=AGGR>",
		"<option value='' selected>None</option>";
		for ($i = 1; $i < 10; $i++)
			echo "<option value=$i",$this->MYREQUEST['AGGR']==$i ? " selected":"",">$i</option>";
		echo '</select>',
		'&nbsp;<input type=submit value="GO!">',
		'</form></div>',

		'<div class="info"><table cellspacing=0><tbody>',
		'<tr>',
		'<th>',SystemHtml::sortheader('S','Directory Name',	"&OB=".$this->MYREQUEST['OB']),'</th>',
		'<th>',SystemHtml::sortheader('T','Number of Files',"&OB=".$this->MYREQUEST['OB']),'</th>',
		'<th>',SystemHtml::sortheader('H','Total Hits',	"&OB=".$this->MYREQUEST['OB']),'</th>',
		'<th>',SystemHtml::sortheader('Z','Total Size',	"&OB=".$this->MYREQUEST['OB']),'</th>',
		'<th>',SystemHtml::sortheader('C','Avg. Hits',	"&OB=".$this->MYREQUEST['OB']),'</th>',
		'<th>',SystemHtml::sortheader('A','Avg. Size',	"&OB=".$this->MYREQUEST['OB']),'</th>',
		'</tr>';

	// builds list with alpha numeric sortable keys
	$tmp = $list = array();
	foreach($this->cache[$this->scope_list[$this->MYREQUEST['SCOPE']]] as $entry)
	{
		$n = dirname($entry['filename']);
		if ($this->MYREQUEST['AGGR'] > 0)
		{
			$n = preg_replace("!^(/?(?:[^/\\\\]+[/\\\\]){".($this->MYREQUEST['AGGR']-1)."}[^/\\\\]*).*!", "$1", $n);
		}
		if (!isset($tmp[$n]))
		{
			$tmp[$n] = array('hits'=>0,'size'=>0,'ents'=>0);
		}
		$tmp[$n]['hits'] += $entry['num_hits'];
		$tmp[$n]['size'] += $entry['mem_size'];
		++$tmp[$n]['ents'];
	}

	foreach ($tmp as $k => $v)
	{
		switch($this->MYREQUEST['SORT1'])
		{
			case 'A': $kn=sprintf('%015d-',$v['size'] / $v['ents']);break;
			case 'T': $kn=sprintf('%015d-',$v['ents']);		break;
			case 'H': $kn=sprintf('%015d-',$v['hits']);		break;
			case 'Z': $kn=sprintf('%015d-',$v['size']);		break;
			case 'C': $kn=sprintf('%015d-',$v['hits'] / $v['ents']);break;
			case 'S': $kn = $k;					break;
		}
		$list[$kn.$k] = array($k, $v['ents'], $v['hits'], $v['size']);
	}

	if ($list) {
		// sort list
		switch ($this->MYREQUEST['SORT2'])
		{
			case "A":	krsort($list);	break;
			case "D":	ksort($list);	break;
		}
		// output list
		$i = 0;
		foreach($list as $entry) {
			echo
				'<tr class=tr-',$i%2,'>',
				"<td class=td-0>",$entry[0],'</a></td>',
				'<td class="td-n center">',$entry[1],'</td>',
				'<td class="td-n center">',$entry[2],'</td>',
				'<td class="td-n center">',$entry[3],'</td>',
				'<td class="td-n center">',round($entry[2] / $entry[1]),'</td>',
				'<td class="td-n center">',round($entry[3] / $entry[1]),'</td>',
				'</tr>';

			if (++$i == $this->MYREQUEST['COUNT']) break;
		}
		
	}
	else
	{
		echo '<tr class=tr-0><td class="center" colspan=6><i>No data</i></td></tr>';
	}

	echo "</tbody></table>";

	if ($list && $i < count($list))
	{
		echo "<a href=\"$MY_SELF&OB=",$this->MYREQUEST['OB'],"&COUNT=0\"><i>",count($list)-$i,' more available...</i></a>';
	}
?>

</div>