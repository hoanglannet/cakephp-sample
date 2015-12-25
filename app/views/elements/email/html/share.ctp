<?php
	Configure::load('appconfig');
	$webroot = Configure::read('App.CONTEXT_PATH');
?>
<b>You have been shared some spaces</b><br>
<?php
	$i = 1;
	foreach($spaces as $space) { 
?>
<br>
<table border="1" width="100%">
	<tr><td>
		<b>Space: <?php echo $space['Space']['address'].', '.$space['City']['name'].', '.$space['City']['State']['name'];?></b>
	</td></tr>
	<tr><td>
		<b>Space Information</b><br>
		<table>
			<tr>
				<td>Address</td>
				<td><?php echo $space['Space']['address'].', '.$space['City']['name'].', '.$space['City']['State']['name'];?></td>
			</tr>
			<tr>
				<td>Zip Code</td>
				<td><?php echo $space['Space']['zip_code'];?></td>
			</tr>
			<tr>
				<td>Floor(s)</td>
				<td><?php echo $space['Space']['floor'];?></td>
			</tr>
			<tr>
				<td>Square Footage</td>
				<td><?php echo $space['Space']['square_footage'];?></td>
			</tr>
			<tr>
				<td>Availability</td>
				<td><?php echo $space['Space']['availability'];?></td>
			</tr>
			<tr>
				<td>Space</td>
				<td><?php Configure::load('appconfig');
						$typeSpace = Configure::read('TypeSpace');
						$typeLease = Configure::read('TypeLease');
						echo $typeSpace[$space['Space']['space']];
					?>
				</td>
			</tr>
			<tr>
				<td>Pricing</td>
				<td>
					<?php
						$totalYear = '';
						$totalMonth = '';
						if (!empty($space['Space']['price_monthly'])) { 
							echo $space['Space']['price_monthly'].' psf/month'.'<br>';
							$totalMonth = $space['Space']['price_monthly']*$space['Space']['square_footage'];
						}
						if (!empty($space['Space']['price_year'])) { 
							echo $space['Space']['price_year'].' psf/year';
							$totalYear = ($space['Space']['price_year']*$space['Space']['square_footage'])/12;
						}
					?>				
				</td>
			</tr>
			<tr>
				<td>Approximate Total Monthly Price (by Month)</td>
				<td><?php echo round($totalMonth, 2);?></td>
			</tr>
			<tr>
				<td>Approximate Total Monthly Price (by Year)</td>
				<td><?php echo round($totalYear, 2);?></td>
			</tr>
			<tr>
				<td>Type of Lease</td>
				<td><?php echo $typeLease[$space['Space']['type_lease']];?></td>
			</tr>
			<tr>
				<td>Rating</td>
				<td><?php echo $space['Space']['rating']; ?></td>
			</tr>
			<tr>
				<td>General Space Note:</td>
				<td><?php echo $space['Space']['general_space_note'];?></td>
			</tr>
		</table>
	</td></tr>
	<tr><td>
		<b>Detail Notes</b><br>
		<table>
			<?php
				$listNote = $space['SpaceNote'];
			    foreach ($listNote as $item) {
					$spaceNoteID = isset($item['id'])? $item['id'] : '';
					$answer = isset($item['answer']) &&($item['answer'] == 1) ? 'true' : '';			    
			?>			
				<tr>
					<td>
						<?php echo $this->Form->input($item['SpaceNoteTemplate']['id'].'__'.$spaceNoteID, array(
							'type' => 'checkbox', 
							'checked' => $answer, 
							'label' => $item['SpaceNoteTemplate']['question'],)); 
						?>					
					</td>
				</tr>
			<?php } ?>
		</table>		
	</td></tr>
	<tr><td>
		<b>Images Of This Space</b><br>
		<table>
			<?php
				$images = array();
				$videos = array();
				foreach( $space['Media'] as $item) {
					if ($item['type'] == MEDIA_IMAGE) {
						$images[] = $item;
					} else {
						$videos[] = $item;
					}
				}
				foreach($images as $image) {
			?>		
				<tr>
					<td>
						<?php echo '<a id="imageID_'.$image["id"].'" href="'.$webroot.$image["link"].'" rel="prettyPhoto[gallery2]" title=""> 
									<img src="'.$webroot.$image["link"].'" height="200" alt="" border=0/></a>';
						?>					
					</td>
				</tr>
			<?php } ?>
		</table>	
	</td></tr>
	<tr><td>
		<b>Videos Of This Space</b><br>
		<?php
			if (!empty($videos)) {
				echo '<input type="hidden" id="currentVideoID" value="videoID_'.$videos[0]["id"].'"/>';
			} 
		?>			
		<table>
			<?php foreach($videos as $video) { ?>
				<tr>
					<td>
						<?php echo '<a href="' . $video['link'] . '">' . $video['link'] . '</a>'; ?>
					</td>
				</tr>
			<?php } ?>
		</table>	
	</td></tr>
</table>
<?php }?>
<br>
<b>Mkbexcited Support</b>