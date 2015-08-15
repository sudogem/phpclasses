<?php
/**
 * @author		Arman Ortega <arman.ortega@yahoo.com> 
 * @version   v1.0
 * @created		Dec 20 2006
 */

class pager
{

	function getPagerData(  $page, $totalrows, $limit, $paging, $scroll, $scrollnumber )
	{
	$last = '' ;	
	$first = '' ;
	$pagelinks = '' ;
		if ($page > 1 ){
			$previous = $page - 1;
			$pagelinks .= '<a href="' . $_SERVER['PHP_SELF'] . '?page=1' . '"><< Start</a>';
			$pagelinks .= '<a href="' . $_SERVER['PHP_SELF'] . '?page=' . $previous . '"> &lt;Previous</a>';
		}
		
		if ($totalrows != $limit) {
				if ($scroll == 1) {
					if ($paging > $scrollnumber) {
							$first = $page;
							$last = ($scrollnumber - 1) + $page;
					}
				}
				else {
					$first = 1;
					$last = $paging;
				}
				if ($last > $paging ) {
					$first = $paging - ($scrollnumber - 1);
					$last = $paging;
				}
				for ($i = $first;$i <= $last;$i++){
					if ($page == $i) {
						$pagelinks .= ' <b class="black"> ';  
						$pagelinks .= $i;
						$pagelinks .= ' </b> ';
					} else {
						$pagelinks .= '<a href="'. $_SERVER['PHP_SELF'] . '?page='. $i . '"> '. $i .' </a>';
					}
				}
		}
		if ($page < $paging) {
				$next = $page + 1;
				$pagelinks .= '<a href="' . $_SERVER['PHP_SELF'] . '?page=' . $next .'">Next></a>';
				$pagelinks .= '<a href="' . $_SERVER['PHP_SELF'] . '?page=' . $paging . '">End >></a>';
		}
		$this->pagelinks = $pagelinks;
		return $this->pagelinks;
	
	}
}
?>