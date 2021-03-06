<?php
/**
 * research object
 */ 

global $CONFIG;
$page_owner = elgg_get_page_owner_entity();
if ($page_owner === false || is_null($page_owner)) {
  $page_owner = elgg_get_logged_in_user_entity();
  elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());
}

$full = (elgg_get_context () != "view") ? false : true;
// compact is for very compact listings with edit and delete links ; disables full view
$compact = (elgg_get_context () != "index") ? false : true;
$url = $CONFIG->url;

 // set default CVR values
  $cvr_array = set_defaultcvr ();

// Full and listing views
if (!$compact) {
  echo elgg_view('resume/single_menu');
  ?>

  <div>
    <p>
      <?php  // Scoring
      $importance = (int) resscore ($vars['entity'], $cvr_array);
      
      echo elgg_view('resume/importancebar', array('importance' => $importance, 'text' => "Score: $importance/100")); 
     ?>
     
      <h3><a href="<?php echo $vars['entity']->getURL(); ?>">
         <?php echo $vars['entity']->heading; ?></a>
        
      <?php echo elgg_echo('resume:view:at'); ?>
        
      <?php echo '<a href="' . $url . 'search/?tag=' . $vars['entity']->structure . '">'
                . $vars['entity']->structure; ?></a></h3>
        
     <?php
      echo " (" . $vars['entity']->startdate . " &rarr; ";
      if ($vars['entity']->ongoing == 'ongoing') echo elgg_echo('resume:date:now'); 
      else echo $vars['entity']->enddate;
      echo ")";
      ?>
    </p>
    
    <?php
    if ($full) {
      // Full view
      echo '<br />';
      if ($vars['entity']->heading) { 
          echo '<p><strong>' . elgg_echo('resume:research:heading') . ' :</strong> <a href="' 
                  . $url . 'search/?tag=' . $vars['entity']->heading . '">' 
                  . $vars['entity']->heading . '</a></p>'; 
      }
      
      if ($vars['entity']->structure) { 
          echo '<p><strong>' . elgg_echo('resume:research:structure') . ' :</strong> <a href="' 
                  . $url . 'search/?tag=' . $vars['entity']->structure . '">' 
                  . $vars['entity']->structure . '</a></p>'; 
      } 
      
      if ($vars['entity']->level) { 
          echo '<p><strong>' . elgg_echo('resume:workexperience:level') . ' :</strong> ' 
                  . elgg_echo('resume:work:level:' . $vars['entity']->level) . '</p>';
      } 
      
      if ($vars['entity']->field) { 
          echo '<p><strong>' . elgg_echo('resume:research:field') . ' :</strong> ' 
                  . elgg_echo('resume:research:' . $vars['entity']->field) . '</p>'; 
      }
      
      if ($vars['entity']->prizes) { 
          echo '<p><strong>' . elgg_echo('resume:research:prize') . ' :</strong> '; 
             $prizes = $vars['entity']->prizes; 
          if (is_array($prizes)) {
             foreach ($prizes as $prkey => $prvalue) {
                 if ($i > 0) echo ", ";
                 echo elgg_echo('resume:research:prize:'.$prvalue);
                 $i++;
             }
          }
          else echo elgg_echo('resume:research:prize:'.$prizes);
          echo '</p>'; 
      }
      
      if ($vars['entity']->articles) {
          $articles_array = $vars['entity']->articles;
          $citations_array = $vars['entity']->citations;
          $maxcitations_array = $vars['entity']->maxcitations;
          $journals_array = $vars['entity']->journals;
          $impacts_array = $vars['entity']->impacts;
          $maximpacts_array = $vars['entity']->maximpacts;
          $eigens_array = $vars['entity']->eigens;
          $maxeigens_array = $vars['entity']->maxeigens;
          $authors_array = $vars['entity']->authors;
          $positions_array = $vars['entity']->positions;
          $ends_array = $vars['entity']->ends;
          
      $count = count($articles_array);	
      echo '<p><strong>' . elgg_echo('resume:research:articles') . '</strong>:</p> <ul>';
      
         for ($i = 0; $i < $count; $i++) {
           // print only if there is some valuable information:
           if (($articles_array[$i] != "") || ($citations_array[$i] != "") || ($impacts_array[$i] != "") || ($eigens_array[$i] != "")) {
             echo '<li><strong>' . elgg_echo('resume:research:article') . '</strong>: '. $articles_array[$i];
             
                    if ($ends_array[$i]) {
                         echo ' (' . $ends_array[$i] . ')';
                    }         
                    
             echo '; <strong>' . elgg_echo('resume:research:citation') . '</strong>: ' . $citations_array[$i]
                  . '; <strong>' . elgg_echo('resume:research:maxcit') . '</strong>: ' . $maxcitations_array[$i]
                  . '; <strong>' . elgg_echo('resume:research:journal') . '</strong>: ' . $journals_array[$i]
                  . ' (<strong>' . elgg_echo('resume:research:impact') . '</strong>: ' . $impacts_array[$i]
                  . '/' . $maximpacts_array[$i]
                  . ', <strong>' . elgg_echo('resume:research:eigen') . '</strong>: ' . $eigens_array[$i]
                  . '/' . $maxeigens_array[$i] . ')'
                  . '; <strong>' . elgg_echo('resume:research:position') . ' / ' . elgg_echo('resume:research:author') . '</strong>: ' 
                  . $positions_array[$i] . '/' . $authors_array[$i] . '</li>'; 
           }
         }
      echo '</ul><br />';
      }
      
      
      if ($vars['entity']->contact) { 
          echo '<p><strong>' . elgg_echo('resume:education:contact') . ' :</strong> ' 
                  . $vars['entity']->contact . '</p>'; 
      }
      
      if ($vars['entity']->description) { 
          echo '<p><strong>' . elgg_echo('resume:research:description') . ' :</strong> ' 
                  . $vars['entity']->description . '</p>'; 
      }
      echo '<br />';
    } else {
       //Listing view
      echo '<strong><a href="' . $vars['entity']->getURL() . '">';
      echo elgg_echo('resume:view:more');
      echo '</a></strong>';
    }
    
    echo '<p>';
      // Edit & delete links
      if (($page_owner->guid == elgg_get_logged_in_user_entity()->guid) && (elgg_get_context() != "profileprint")) {
        echo '<a href="' . $vars['url'] . 'mod/resume/research.php?id=' . $vars['entity']->getGUID() . '">' . elgg_echo('resume:edit') . '</a>&nbsp; ';
        echo elgg_view("output/confirmlink", array( 'href' => $vars['url'] . "action/resume/delete?id=" . $vars['entity']->getGUID(),
            'text' => elgg_echo('resume:delete'), 'confirm' => elgg_echo('resume:delete:element'), )) . '&nbsp; ';
        echo elgg_view("editmenu", array('entity' => $vars['entity'])); // Allow the menu to be extended
      }
      if (!$full && (elgg_get_context () != "profileprint")) {
        $num_comments = $vars['entity']->countComments();
        echo '<a href="' . $vars['entity']->getURL() . '">' . sprintf(elgg_echo("comments")) . ' (' . $num_comments . ')</a><br />';
      }
    echo '</p>';
    ?>
    
    <!-- Comments features -->
    <?php if ($full) { echo elgg_view_comments($vars['entity']); } ?>
    <!-- End of Comments features -->

  </div>
  <?php
} else {
  // Compact view : edit & delete links
  if ($page_owner->guid == elgg_get_logged_in_user_entity()->guid) {
    echo '<a href="' . $vars['url'] . 'mod/resume/research.php?redirect=research&id=' . $vars['entity']->getGUID() . '" title="' . elgg_echo('edit') . '">' . date('m/Y', $vars['entity']->startdate) . "&rarr;";
    if ($vars['entity']->ongoing == 'ongoing') echo elgg_echo('resume:date:now'); else echo date('d/m/Y', $vars['entity']->enddate);
    echo '&nbsp;: ' . $vars['entity']->heading . '</a> &nbsp; ' 
      . '<b>' . elgg_view("output/confirmlink", array( 'href' => $vars['url'] . "action/resume/delete?id=" . $vars['entity']->getGUID(), 'text' => 'x', 'confirm' => elgg_echo('resume:delete:element'), 'title' => elgg_echo('delete'))) . '</b>';
  } else {
    echo '<a href="' . $vars['entity']->getURL() . '">' . $vars['entity']->heading . '</a>';
  }
}
