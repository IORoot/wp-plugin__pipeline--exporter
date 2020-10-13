<?php

namespace ex;

class export
{

    use utils;
    use debug;
    
    /**
     * options variable
     * 
     * Contains all of the "save" options for this instance.
     *
     * @var array
     */
    public $options;
    public $auth;

    /**
     * collection variable
     * 
     * Contains all the results of each stage of the
     * universal exporter process.
     *
     * @var array
     */
    public $collection;

    /**
     * results variable
     *
     * What will be placed into the $collection
     * array once this stage is complete.
     * 
     * @var array
     */
    public $results;


    public function set_options($options)
    {
        $this->options = $options['ex_job_export_id'];
    }


    public function set_auth($auth)
    {
        $this->auth = $auth;
    }

    public function set_collection($collection)
    {
        $this->collection = $collection['ex\content'];
    }

    public function run()
    {
        $this->debug_clear('export');
        $this->get_moustaches();
        if ($this->is_disabled()){ return; }
        $this->loop_through_exporters();
        return $this->results;
    }

    //  ┌─────────────────────────────────────────────────────────────────────────┐
    //  │                                                                         │░
    //  │                                                                         │░
    //  │                                 PRIVATE                                 │░
    //  │                                                                         │░
    //  │                                                                         │░
    //  └─────────────────────────────────────────────────────────────────────────┘░
    //   ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░


    private function loop_through_exporters()
    {
        foreach($this->options['ex_export_target_mapping'] as $this->current_exporter)
        {
            $this->add_auth();
            $this->run_exporter();
        }
    }

    private function  add_auth()
    {
        $this->current_exporter['auth'] = $this->auth;
    }


    private function run_exporter()
    {
        $exporterName = $this->current_exporter['acf_fc_layout'];
        $exporterClass = 'ex_'.$exporterName;

        $exporter = new $exporterClass;
        $exporter->set_options($this->current_exporter);
        $exporter->set_data($this->collection);

        $this->results = $exporter->run();

    }




    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                                                         │░
    // │                                                                         │░
    // │                                 CHECKS                                  │░
    // │                                                                         │░
    // │                                                                         │░
    // └─────────────────────────────────────────────────────────────────────────┘░
    //  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░


    private function is_disabled()
    {
        if ($this->options['ex_export_group']['ex_export_enabled'] == false)
        {
            return true;
        }
        return false;
    }


    //  ┌─────────────────────────────────────────────────────────────────────────┐
    //  │                                                                         │░
    //  │                                                                         │░
    //  │                            Render Moustaches                            │░
    //  │                                                                         │░
    //  │                                                                         │░
    //  └─────────────────────────────────────────────────────────────────────────┘░
    //   ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░

    private function get_moustaches()
    {
        $moustache_array = array();

        $flattened_array = $this::array_flat($this->collection);
        $all_keys = array_keys($flattened_array);
        
        foreach($all_keys as $key => $value)
        {
            $new_key = preg_replace('/^image_/', 'image:', $value);
            $moustache_array[] = preg_replace('/^post_/', '', $new_key);
        }

        $moustache_array = implode('}}</div> <div class="ex__moustache">{{',$moustache_array);
        $moustache_array = '<div class="ex__moustache">{{'.$moustache_array.'}}</div>';

        $field = new \update_acf_options_field;
        $field->set_field('field_5f7c1268d2959');
        $field->set_value('message', $moustache_array);
        $field->run();
    
    }

}