<p class="input-wrap">
                Count:  
                <input name="<?php echo $this->get_field_name( 'count' ); ?>" style="width: 40px" type="text" value="<?php echo $set['count'];?>" /> &nbsp;&nbsp;
                Skip: 
                <input name="<?php echo $this->get_field_name( 'skip' ); ?>"style="width: 60px" type="text" value="<?php echo $set['skip'];?>" />
            </p>
            <p class="input-wrap">
            	<input class="hidden-input" type="checkbox" name="<?php echo $this->get_field_name( 'display_title' ); ?>" checked="checked" value="0" /> 
                <input type="checkbox" name="<?php echo $this->get_field_name( 'display_title' ); ?>" value="1" <?php checked( $set['display_title'], 1 ); ?> /> Display Title <br />
                
                <input class="hidden-input" type="checkbox" name="<?php echo $this->get_field_name( 'display_excerpt' ); ?>" checked="checked" value="0" /> 
                <input type="checkbox" name="<?php echo $this->get_field_name( 'display_excerpt' ); ?>" value="1" <?php checked( $set['display_excerpt'], 1 ); ?> /> Display Summary Text<br />
                
                <input class="hidden-input" type="checkbox" name="<?php echo $this->get_field_name( 'display_content' ); ?>" checked="checked" value="0" /> 
                <input type="checkbox" name="<?php echo $this->get_field_name( 'display_content' ); ?>" value="1" <?php checked( $set['display_content'], 1 ); ?>/> Display Full Text<br />
                
                <input class="hidden-input" type="checkbox" name="<?php echo $this->get_field_name( 'display_image' ); ?>" checked="checked" value="0" /> 
                <input type="checkbox" name="<?php echo $this->get_field_name( 'display_image' ); ?>" value="1"  <?php checked( $set['display_image'], 1 ); ?>/> Display Image<br />
                
                <input class="hidden-input" type="checkbox" name="<?php echo $this->get_field_name( 'display_link' ); ?>" checked="checked" value="0" /> 
                <input type="checkbox" name="<?php echo $this->get_field_name( 'display_link' ); ?>" value="1" <?php checked( $set['display_link'], 1 ); ?>/> Link to Content<br />
                <span class="input-helper">*** Options may not be applicable to selected display style.</span>
            </p>