<?php 

namespace App\Blocks;

use Roots\Acorn\Application;
use StoutLogic\AcfBuilder\FieldsBuilder;
use WpSageBlocks\Blocks\BaseHero001;

class Hero001 extends BaseHero001
{
    public function __construct(Application $app)
    {
        parent::__construct($app);

        $this->name = 'Hero 001';
        $this->description = 'A simple Video Hero block.';
        $this->category = 'formatting';
        $this->post_types = [];
        $this->example = [
            'background_video' => 'http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4',
            'background_image' => [
                'url' => 'https://picsum.photos/1920/1080'
            ],
            'logo' => [
                'url' => 'https://picsum.photos/200/200'
            ],
            'heading' => 'Heading',
            'subheading' => 'Subheading',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam euismod, nisl eget fermentum aliquam, odio nibh ultricies',
            'show_arrow_down' => false,
        ];
    }

    public function fields()
    {
        $videoHero = new FieldsBuilder('video_hero');

        $videoHero
            ->addFile('background_video',[
                'label' => 'Background Video',
                'instructions' => 'Select an MP4 video from the media library.',
                'return_format' => 'url',
                'library' => 'all',
                'mime_types' => 'mp4',
            ])
            ->addImage('background_image', [
                'label' => 'Background Image',
                'instructions' => 'Select an image from the media library.',
                'library' => 'all',
                'mime_types' => 'jpg, png, svg, gif',
            ])
            ->addImage('logo', [
                'label' => 'Logo',
                'instructions' => 'Select an image from the media library.',
                'library' => 'all',
                'mime_types' => 'jpg, png, svg, gif',
            ])
            ->addText('heading', [
                'label' => 'Heading'
            ])
            ->addText('subheading', [
                'label' => 'Subheading'
            ])
            ->addWysiwyg('description', [
                'label' => 'Description',
                'media_upload' => false,
                'toolbar' => 'basic',
                'delay' => 1,
            ])

            ->addRepeater('buttons')
                ->addLink('link', [
                    'label' => 'Link',
                    'instructions' => 'Select a link.',
                ])
            ->endRepeater()
            ->addTrueFalse('show_arrow_down', [
                'default' => false
            ])
            ;

        return $videoHero->build();
    }
}