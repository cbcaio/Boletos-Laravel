# Image Attacher

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

This package uses polymorphic relationships to easily attach images to models. Basically you just need to use one of 
  the package's traits in your model and you are good to go(see Usage). What happens in the background is that the 
  models are linked to the images by a MorphOne relationship and every time you persist a image 
  into the associated table the file is written automatically using the selected driver (using [Flysystem]), or updated if 
  needed.

## Install

### 1 - Via Composer

To get started with Image Attacher, add it to your `composer.json` file as a dependency:

``` bash
$ composer require cbcaio/image-attacher
```

### 2 - Provider

After installing the Image Attacher, register the `CbCaio\ImgAttacher\Providers\ImgAttacherServiceProvider` 
in your configuration file (`config/app.php`):

    'providers' => [
        // Other service providers...

        CbCaio\ImgAttacher\Providers\ImgAttacherServiceProvider::class,
    ],
    
### 3 - Configuration

In order to publish the configuration files run the following command

``` bash
$ php artisan vendor:publish
```
    
This command will create 3 new files:
 
 1. `config/img-attacher.php` : this file holds the Image Attacher configurations. 
     - `path_to_save`: defines where images will be saved, relatively to the driver and local specified in the 
     `flysystem.php`. This path will be parsed before being used. The :`attribute` references information about the 
     `attacherImage` model while the :`owner_class` and :`owner_id` are relative to the owner class of 
     the relationship. These are needed to organize folders and also to certify that the right image will be 
     deleted.
     
       IMPORTANT: This path should be exclusively used by the package, do not put other files in the same folder 
       otherwise they can be deleted by mistake.
     
     - `processing_styles` and `processing_style_routines`: the 'routine' represents a sequence of 'styles' and its needed 
     methods. Each 'style' saves a copy of the original image with the modifications specified in its method. They can
      be used to save different versions of your original image automatically when you add an image to a model (the 
      model can can have different 'versions'/'styles' of same image with only one relationship). 
        - For example, the following code will save the original image and also a 'thumbnail 
        version' of the same image in their respective parsed `path_to_save` with :`style` substituted by 
        `original_style` and `thumbnail`:  
          
          ```
             $model->addImage(uploaded_file,'default_routine');
             ....
              'default_routine' =>
              [
                  'original_style' => function ($image) {
                      return $image;
                  },
                  'thumbnail' => function ($image) {
                     $image->resize(null, 500, function ($constraint) {
                         $constraint->aspectRatio();
                         $constraint->upsize();
                     });
                     return $image;
                  },
              ]
          ```
 2. `database/migrations/2016_01_12_000000_create_attacher_images_table` : this is a migration file to create
  the table which will hold the reference to your models in the MorthOne relationship and the information about the 
  images.
  
 3. `config/flysystem.php` : this file is relative to the [Flysystem]. How and where your files will be written is 
 defined here. It is important to say that this package is only tested using the 'local' driver.

## Usage

To start attaching images to your models you just need to use one of the available traits (currently only `hasImage`) in 
the model.

     class RandomModel extends Model
     {
         use hasImage;
     }

### 1 - Basic of using the hasImage trait

##### Adding and retrieving an image to the model from an uploaded file.

    ```php
        $upload = Input::file('image');
        $model->addImage($upload);
        ...
        $image = $user->getImage();
        // Path is relative
        $image->getPath('original_style);
        $image->getPath('thumbnail);
        // Url includes full path
        $image->getUrl('original_style);
        $image->getUrl('thumbnail);
    ```
  
##### Adding another image
    ```php
        // The same as adding, the package will identify if the model already has an image, delete the previous 
        images and update the relationship.
        $upload = Input::file('image2');
        $model->addImage($upload);
    ```
    
##### Deleting image and all the its styles
    ```php
        $model->deleteImage();
    ```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email :author_email instead of using the issue tracker.

## Credits

- [CbCaio][link-author]
- [vinicius73][link-vinicius]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/CbCaio/Image-Attacher.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/CbCaio/Image-Attacher/master.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/CbCaio/Image-Attacher.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/CbCaio/Image-Attacher.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/CbCaio/Image-Attacher
[link-travis]: https://travis-ci.org/CbCaio/Image-Attacher
[link-code-quality]: https://scrutinizer-ci.com/g/CbCaio/Image-Attacher
[link-downloads]: https://packagist.org/packages/CbCaio/Image-Attacher
[link-author]: https://github.com/CbCaio
[link-contributors]: ../../contributors
[Flysystem]: https://github.com/GrahamCampbell/Laravel-Flysystem
[link-vinicius]: https://github.com/vinicius73