<?php

namespace App\Http\Controllers;

use App\Http\Requests\Flat\UploadFlatRequest;
use App\Models\Flat;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Support\LazyCollection;
use App\Models\File;
use App\Models\User;
use App\Utils\EmptyUtil;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use SimpleXMLElement;

class FlatUploadController extends Controller
{
    private static function uploadCreateProperties($property_values, $property_id, Flat $flat)
    {
        if (!empty($property_values)) return;

        if (is_array($property_values)) {
            foreach ($property_values as $item) {
                Flat::propertiesCreate($flat, 'value_enum', $item, (int) $property_id);
            }

            return;
        }

        Flat::propertiesCreate($flat, 'value_enum', $property_values, (int) $property_id);
    }

    public function upload(UploadFlatRequest $requst)
    {
        $xml_data = new SimpleXMLElement(
            file_get_contents($requst->file)
        );

        LazyCollection::make(function () use ($xml_data) {
            foreach ($xml_data->objects->object as $item) {
                yield EmptyUtil::clearValues(
                    Json::decode(
                        Json::encode($item),
                        true
                    )
                );
                // yield (object) [
                //     // 'data' => [
                //     //     'id $item?->id,
                //     //     'object_id $item?->object_id,
                //     //     'type_id' => $item?->type_id,
                //     //     'country_id' => $item?->country_id,
                //     //     'district_id' => $item?->district_id,
                //     //     'district' => $item?->district,
                //     //     'address' => $item?->address,
                //     //     'longitude' => $item?->longitude,
                //     //     'latitude' => $item?->latitude,
                //     //     'currency_id' => $item?->currency_id,
                //     //     'price' => $item?->price,
                //     //     'price_per_meter' => $item?->price_per_meter,
                //     //     'price_day' => $item?->price_day,
                //     //     'price_week' => $item?->price_week,
                //     //     'price_month' => $item?->price_month,
                //     //     'not_show_price' => $item?->not_show_price == 'None' ? 0 : 1,
                //     //     'rooms' => $item?->rooms,
                //     //     'bedrooms' => $item?->bedrooms,
                //     //     'bathrooms' => $item?->bathrooms,
                //     //     'square' => (int) $item?->square,
                //     //     'square_land' => $item?->square_land,
                //     //     'square_land_unit' => $item?->square_land_unit,
                //     //     'floor' => $item?->floor,
                //     //     'total_floor' => $item?->total_floor,
                //     //     'building_type' => $item?->building_type,
                //     //     'building_date' => $item?->building_date,
                //     //     'contact_id' => $item?->contact_id,
                //     //     'specialtxt' => $item?->specialtxt,
                //     //     'description' => $item?->description,
                //     //     'filename' => $item?->filename,
                //     //     'tour_link' => $item?->tour_link,
                //     // ],
                //     // 'user' => [
                //     //     'id' => $item->contact_id,
                //     //     'name' => $item->contact->name,
                //     //     'email' => $item->contact->email,
                //     //     'phone' => $item->contact->phone,
                //     //     'avatar' => $item->contact->photo,
                //     // ],
                //     'user' => $item->contact,
                //     'images' => $item->images,
                //     'properties' => $item->properties
                // ];
            }
        })
            ->chunk(250)
            ->each(function ($elem) {
                $elem->each(function ($item) {
                    try {
                        // if ($item->id == 3768540) dd((int) $item?->contact_id);
                        // if ((int) $item?->contact_id != $item?->contact_id) return;
                        if ((int) $item?->contact_id === 0 || empty($item->contact)) return;

                        $item->contact = (object) $item->contact;
                        $user = User::find((int) $item?->contact_id);
                        if ($user) {
                            $user->update([
                                'name' => $item?->contact?->name,
                                'email' => $item?->contact?->email,
                                'phone' => $item?->contact?->phone,
                                'avatar' => EmptyUtil::valueOrNull($item?->contact?->photo, 'string'),
                            ]);
                        } else {
                            $user = User::create([
                                'id' => (int) $item?->contact_id,
                                'name' => $item?->contact?->name,
                                'email' => $item?->contact?->email,
                                'password' => Hash::make(Str::random(random_int(10, 14))),
                                'phone' => $item?->contact?->phone,
                                'avatar' =>  EmptyUtil::valueOrNull($item?->contact?->photo, 'string'),
                            ]);
                        }

                        $user->contacts()->delete();
                        if (!empty($item?->contact) && !empty($item?->contact->messengers)) {
                            foreach (explode(',', $item?->contact->messengers) as $messager) {
                                $user->contacts()->create([
                                    'type' => $messager
                                ]);
                            }
                        }

                        $flat = Flat::firstOrCreate(
                            ['id' => $item?->id],
                            [
                                'id' => $item?->id,
                                'object_id' => $item?->object_id,
                                'type_id' => $item?->type_id,
                                'country_id' => EmptyUtil::valueOrNull($item?->country_id ?? null, 'int'),
                                'district_id' => $item?->district_id,
                                'district' => $item?->district ?? null,
                                'address' => $item?->address ?? null,
                                'longitude' => $item?->longitude ?? null,
                                'latitude' => $item?->latitude ?? null,
                                'currency_id' => $item?->currency_id,
                                'price' => $item?->price,
                                'price_per_meter' => (float) $item?->price_per_meter,
                                'price_day' => EmptyUtil::valueOrNull($item?->price_day ?? null, 'float'),
                                'price_week' => EmptyUtil::valueOrNull($item?->price_week ?? null, 'float'),
                                'price_month' => EmptyUtil::valueOrNull($item?->price_month ?? null, 'float'),
                                'not_show_price' => $item?->not_show_price == 'None' ? 0 : 1,
                                'rooms' => EmptyUtil::valueOrNull($item?->rooms ?? null, 'int'),
                                'bedrooms' =>  EmptyUtil::valueOrNull($item?->bedrooms ?? null, 'int'),
                                'bathrooms' => EmptyUtil::valueOrNull($item?->bathrooms ?? null, 'int'),
                                'square' =>  (float) $item?->square,
                                'square_land' => EmptyUtil::valueOrNull($item?->square_land ?? null, 'float'),
                                'square_land_unit' => EmptyUtil::valueOrNull($item?->square_land_unit ?? null, 'float'),
                                'floor' => EmptyUtil::valueOrNull($item?->floor ?? null, 'int'),
                                'total_floor' => EmptyUtil::valueOrNull($item?->total_floor ?? null, 'int'),
                                'building_type' => $item?->building_type,
                                'building_date' => $item?->building_date ?? null,
                                'contact_id' => (int) $item?->contact_id,
                                'specialtxt' => $item?->specialtxt ?? null,
                                'description' => $item?->description ?? null,
                                'filename' => $item?->filename ?? null,
                                'tour_link' => $item?->tour_link ?? null,
                            ]
                        );

                        $flat->files()->delete();
                        if (!empty($item->images)) {
                            $item->images = (object) $item?->images;
                            if (!empty($item?->images->image)) {
                                foreach ($item?->images->image as $image) {
                                    $image = (object) $image;

                                    $file = File::firstOrCreate([
                                        'path' => (string) $image->filename[0],
                                        'type' => 'image/' . pathinfo($image->filename[0], PATHINFO_EXTENSION),
                                        'user_id' => (int) $item?->contact_id
                                    ]);

                                    $flat->files()->create([
                                        'file_id' => $file->id
                                    ]);
                                }
                            }
                        }

                        $flat->flat_properties()->delete();
                        if (!empty($item->properties)) {
                            $item->properties = (object) $item?->properties;

                            if (!empty($item?->properties->property)) {
                                foreach ($item?->properties->property as $property) {
                                    $property = (object) $property;

                                    if (isset($property->property_value_enum)) $this::uploadCreateProperties($property->property_value_enum, $property->property_id, $flat);
                                    if (isset($property->property_value)) $this::uploadCreateProperties($property->property_value, $property->property_id, $flat);
                                }
                            }
                        }
                    } catch (Exception $e) {
                        // dd($item);
                    }
                });

                sleep(0.05);
            });
    }
}
