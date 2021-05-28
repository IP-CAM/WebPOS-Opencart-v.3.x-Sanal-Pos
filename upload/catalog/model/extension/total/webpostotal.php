<?php
class ModelExtensionTotalWebposTotal extends Model {
	public function getTotal($total) {
		$this->load->language('extension/total/webpostotal');
		$webpos_title = '';
		$webpos_total = 0;
		if ( isset( $this->session->data['instalment'] ) ) {
			$bank_array = explode( '_', $this->session->data['instalment'] );
			$instalment_array = explode( 'x', $bank_array[1] );
			$instalment = intval( $instalment_array[0] );
			$instalment_array[1] = str_replace( ',', '', $instalment_array[1] );
			$price = $total['total'];
			$ratio = floatval( $this->config->get('total_webpostotal_single_ratio') );
			if ( $instalment != 0 ) {
				$webpos_total = ( $price * $bank_array[2] ) / 100;
				$webpos_title = $this->language->get('text_total') . '(' . $instalment . 'x' . $this->currency->format((float)(($price+$webpos_total)/$instalment)).')';
			} else {
				if ($ratio>0){
					$webpos_title = $this->language->get('text_single_positive').'(%'. $ratio .')';
				} elseif( $ratio < 0 ){
					$webpos_title = $this->language->get('text_single_negative').'(%'. $ratio .')';
				} else {
					$webpos_title = $this->language->get('text_no_commision');
				}
				$webpos_total = $price * $ratio / 100;
			}
			$total['total'] += $webpos_total;
			$total['totals'][] = array(
			'code'       => 'webpostotal',
			'title'      => $webpos_title,
			'value'      => $total['total'],
			'sort_order' => $this->config->get('total_sort_order')+1
			);
		}

	}
}