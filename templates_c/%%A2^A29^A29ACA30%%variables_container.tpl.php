<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>Variable name</th>
        <th>Value</th>
    </tr>
    </thead>
    <tbody>
<?php $_from = $this->_tpl_vars['Variables']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['name'] => $this->_tpl_vars['value']):
?>
	<tr>
		<td><?php echo $this->_tpl_vars['name']; ?>
</td>
		<td><?php echo $this->_tpl_vars['value']; ?>
</td>
	</tr>
<?php endforeach; endif; unset($_from); ?>
</tbody>
</table>