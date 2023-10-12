<?php

require_once(ABSPATH . 'wp-content/plugins/learnpress/inc/admin/class-lp-install-sample-data.php');

function create_lessons(string $body)
{
    $new = str_replace("<pre>", "", $body);
    $new_new = str_replace("</pre>", "", $new);
    $json = json_decode($new_new, true);


    foreach ($json['data'] as $programm) {
        $args = array(
            'fields' => 'ids',
            'post_type' => 'repas',
            'meta_query' => array(
                array(
                    'key' => 'digiforma_id',
                    'value' => $programm['id'],
                )
            )
        );
        $query = new WP_Query($args);
        if (empty($query->have_posts())) {
            $my_post = array(
                'post_type' => 'lp_course',
                'post_title' => json_decode('"' . $programm['name'] . '"'),
                'post_content' => $programm['description'],
                'post_status' => 'publish',
                'meta_input' => array(
                    '_lp_duration' => $programm['durationInHours'],
                    '_lp_price' => $programm['costsInter'][0]['cost'],
                    'digiforma_id' => $programm['id']
                )
            );
            switch ($programm['category']['name']) {
                case 'Big data':
                    $category_id = 78;
                    break;
                case 'Blockchain':
                    $category_id = 79;
                    break;
                case 'Réseaux et systèmes':
                    $category_id = 101;
                    break;
                case 'Bureautique':
                    $category_id = 102;
                    break;
                case 'Certifications':
                    $category_id = 83;
                    break;
                case 'Cloud':
                    $category_id = 80;
                    break;
                case 'Cybersécurité':
                    $category_id = 77;
                    break;
                case 'Design & Communication':
                    $category_id = 99;
                    break;
                case 'Développement':
                    $category_id = 100;
                    break;
                case 'Devops':
                    $category_id = 82;
                    break;
            }
            $post_id = wp_insert_post($my_post);
            $taxonomy = 'course_category';
            wp_set_object_terms($post_id, $category_id, $taxonomy);

            $image_url = $programm['image']['url'];


            $image_name = $programm['name'] . '.jpg';//file name without extension

            $upload_dir = wp_upload_dir(); // Set upload folder
            $image_data = file_get_contents($image_url); // Get image data
            $unique_file_name = wp_unique_filename($upload_dir['path'], $image_name); // Generate unique name
            $filename = basename($unique_file_name); // Create image file name
            if (wp_mkdir_p($upload_dir['path'])) {
                $file = $upload_dir['path'] . '/' . $filename;
            } else {
                $file = $upload_dir['basedir'] . '/' . $filename;
            }
            file_put_contents($file, $image_data);
            $wp_filetype = wp_check_filetype($filename, null);
            $attachment = array(
                'post_mime_type' => $wp_filetype['type'],
                'post_title' => sanitize_file_name($filename),
                'post_content' => '',
                'post_status' => 'inherit'
            );
            $attach_id = wp_insert_attachment($attachment, $file, $post_id);
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $attach_data = wp_generate_attachment_metadata($attach_id, $file);
            wp_update_attachment_metadata($attach_id, $attach_data);
            set_post_thumbnail($post_id, $attach_id);

            $class = new LP_Install_Sample_Data();
            foreach ($programm['steps'] as $step) {
                $section_id = $class->create_section($step['text'], $post_id);
                $i == 1;
                foreach ($step['substeps'] as $substep) {
                    $class->create_lesson($substep['text'], $section_id, $post_id, $i);
                    $i++;
                };
            }
        }
    }
}