<h2>
	<?php echo _x( 'CCPA User logs', 'ccpa-framework' ); ?>
</h2>
<hr>
<?php if ( count( $userlogData ) ) : ?> 
<div class="userlog-scroll">
	<table class="ccpa-user-logs">
		<th><?php echo _x( 'S.no', 'ccpa-framework' ); ?></th>
		<th><?php echo _x( 'User ID', 'ccpa-framework' ); ?></th>
		<th><?php echo _x( 'User logs', 'ccpa-framework' ); ?></th>
		<th><?php echo _x( 'Updated date', 'ccpa-framework' ); ?></th>
		<?php
		$x = 1;foreach ( $userlogData as $item ) :
			$data         = unserialize( $item->userlog );
			$userlog_data = (array) $data;
			unset( $userlog_data['user_pass'] );
			unset( $userlog_data['user_activation_key'] );
			unset( $userlog_data['user_status'] );
			$userid = $userlog_data['ID'];
			unset( $userlog_data['ID'] );
			?>
			<tr>
				<td>            
					<?php echo $x++; ?>
				</td>
				<td>            
					<?php echo esc_html( $userid ); ?>
				</td>
				<td>
					<ul>
						<?php
						if ($userlog_data) {
							foreach ($userlog_data as $key => $detail) {                      
								$key = print_r($key, true);                                   
								$detail = print_r($detail, true);                             
								echo "<li><strong>" . $key . ":</strong>" . $detail . "</li>";
							}                                                                 
						}
						echo '</br>';
						?>
					</ul>
				</td>
				<td>
				<?php echo $item->updated_at; ?>
				
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
	</div>
<?php else : ?>
	<p><?php echo _x( 'No User Logs', 'ccpa-framework' ); ?>.</p>
<?php endif; ?>
