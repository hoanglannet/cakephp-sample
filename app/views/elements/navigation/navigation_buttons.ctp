<div id='navigationButtons'>
	<ul>
		<?php foreach($navigationButtons as $item) { ?>
			<li>
				<?php
					if (isset($item['type'])) {
						if ($item['type'] == 'div') {
							echo $this->Html->div(null, $item['label'], array('id' => $item['id']));
						}
					} else {
						echo $this->Html->link(
							$item['label'],
							$item['url'],
							array()
						);
					} 
				?>
			</li>
		<?php } ?>
	</ul>
</div>

