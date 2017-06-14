<?php
/*
Template Name: Todo List
*/
?>


<?php get_header(); ?>
			
			<div id="content" class="clearfix row">
			
				<div id="main" class="col-sm-8 clearfix" role="main">

					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					
					<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
						
						<header>
							
							<div class="page-header"><h1 class="page-title" itemprop="headline"><?php the_title(); ?></h1></div>
						
						</header> <!-- end article header -->
					
						<section class="post_content clearfix" itemprop="articleBody">
							<?php the_content(); ?>					
						</section> <!-- end article section -->
						
						<footer>
			
							<?php the_tags('<p class="tags"><span class="tags-title">' . __("Tags","wpbootstrap") . ':</span> ', ', ', '</p>'); ?>
							
						</footer> <!-- end article footer -->
					
					</article> <!-- end article -->
					
					
					<?php
					
					$opts=array(
					  'http'=>array(
					    'method'=>"GET",
					    'header'=>"X-Client-ID: e788fd2916479bfdccc8\r\n".
					              "X-Access-Token: 8100a32d008c09b8a5b6966454e400572ee476317a0f9a547bc80545aa8d\r\n"
					  )
					);

					$context = stream_context_create($opts);

					$json = file_get_contents('https://a.wunderlist.com/api/v1/tasks?list_id=286972587', false, $context);
												
					$tasks = json_decode($json);	
						
					?>
					<table class="table table-striped">
						<thead>
							<tr>
								<th>Todos</th>
							</tr>
						</thead>
					<tbody>
					<?php foreach($tasks as $task): ?>
						<tr>
							<td><?php echo $task->title ?></td>
						</tr>
					<?php endforeach; ?>
					</tbody>
					</table>
					
					<?php endwhile; ?>		
					
					<?php else : ?>
					
					<article id="post-not-found">
					    <header>
					    	<h1><?php _e("Not Found", "wpbootstrap"); ?></h1>
					    </header>
					    <section class="post_content">
					    	<p><?php _e("Sorry, but the requested resource was not found on this site.", "wpbootstrap"); ?></p>
					    </section>
					    <footer>
					    </footer>
					</article>
					
					<?php endif; ?>
					
				</div> <!-- end #main -->
    
				<?php get_sidebar(); // sidebar 1 ?>
    
			</div> <!-- end #content -->

<?php get_footer(); ?>