<div class="row">
	</div><?php foreach ($deliveryAdresses as $deliveryAdress): ?> 
			<div class="col-3">
				<div class="card">
					<div class="card-body">
						<strong class="reciepient"><?= $deliveryAdress['reciepient'] ?></strong>
						<p class="street">
		<?= $deliveryAdress['street'] ?><?= $deliveryAdress['street_number'] ?>
						</p>
						<p class="city">
	<?= $deliveryAdress['zip_code'] ?><?= $deliveryAdress['city'] ?>	
				
						</p>
	<a class="card-link" href="index.php/selectDeliveryAddress/<?= $deliveryAdress['id'] ?>">choose</a>	
					</div>
				</div>
			</div>
	</div><?php endforeach;?> 
</div> 
	