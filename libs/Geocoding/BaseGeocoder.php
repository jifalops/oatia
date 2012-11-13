<?php
class BaseGeocoder {
  // http://en.wikipedia.org/wiki/Earth_radius
  const EARTH_RADIUS_MILES = 3958.76;
  
  // http://www.nationalatlas.gov/articles/mapping/a_latlong.html#four
  // actually varies from 68.71 to 69.40 from the equator to the poles, respectively
  const MILES_PER_DEGREE_LAT = 69;

    // http://businesshours.net/oatia/geo/calc_lon.php
  // USA runs from about 25' to 50' latitude (indexes 25-50).
  // Ohio runs from about 38.6 to 42 degrees latitude 
  // and -84.8 to -80.5 degrees longitude
  // For negative degrees (southern hemisphere), use the absolute value.
  public static $MILES_PER_DEGREE_LON = array(
    69.171, 69.16, 69.129, 69.076, 69.003, 68.909, 68.794, 
    68.658, 68.502, 68.325, 68.127, 67.908, 67.669, 67.409, 
    67.129, 66.829, 66.508, 66.167, 65.806, 65.425, 65.025, 
    64.604, 64.164, 63.704, 63.225, 62.727, 62.21, 61.674, 
    61.119, 60.546, 59.954, 59.343, 58.715, 58.069, 57.405, 
    56.724, 56.025, 55.309, 
    
    54.576, 53.827, 53.061, 52.279, 51.481, /* ohio 38-42 */
    
    50.667, 49.838, 48.993, 48.133, 47.259, 46.37, 
    45.467, 44.55, 43.619, 42.674, 41.717, 40.747, 39.764, 
    38.769, 37.762, 36.743, 35.713, 34.672, 33.621, 32.559, 
    31.487, 30.405, 29.313, 28.213, 27.104, 25.987, 24.861, 
    23.728, 22.587, 21.44, 20.286, 19.125, 17.959, 16.787, 
    15.61, 14.428, 13.241, 12.051, 10.856, 9.658, 8.458, 
    7.254, 6.049, 4.841, 3.632, 2.422, 1.211, 0
  );

  
  // TODO Make function that can quickly determine if the distance between two points 
  // is less than a given length. It will serve as a first pass to elimiante locations
  // that don't even come close to the range. Perhaps it would return a min and max
  // lat and lon (lat-lon box) that locations must be within to be considered for the
  // functions below. It should use the array (above) and no trig functions.
  //   After that the more precise functions can be run on the 
  // remaining data set.

  


  /* ===============================================================================
   * The below two functions were taken from:
   * https://github.com/coderjoe/gogeocode/blob/master/src/BaseGeocode.php
   *
   * It is under the MIT Liscense (below). I have included some minor modifications.
   *
    *
   * The MIT License
   *
   * Copyright (c) 2008 Joseph Bauser (coderjoe at coderjoe.net)
   * 
   * Permission is hereby granted, free of charge, to any person obtaining a copy
   * of this software and associated documentation files (the "Software"), to deal
   * in the Software without restriction, including without limitation the rights
   * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
   * copies of the Software, and to permit persons to whom the Software is
   * furnished to do so, subject to the following conditions:
   * 
   * The above copyright notice and this permission notice shall be included in
   * all copies or substantial portions of the Software.
   * 
   * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
   * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
   * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
   * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
   * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
   * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
   * THE SOFTWARE.
   * ===============================================================================
   */


  /**
   * Find the distance between the two latitude and longitude coordinates
   * Where the latitude and longitude coordinates are in decimal degrees format.
   *
   * This function uses the haversine formula as published in the article
   * "Virtues of the Haversine", Sky and Telescope, vol. 68 no. 2, 1984, p. 159
   *
   * References:
   *         http://en.wikipedia.org/w/index.php?title=Haversine_formula&oldid=176737064
   *         http://www.movable-type.co.uk/scripts/gis-faq-5.1.html
   *
   * @param float $lat1 The first coordinate's latitude
   * @param float $ong1 The first coordinate's longitude
   * @param float $lat2 The second coordinate's latitude
   * @param float $long2 The second coordinate's longitude
   * @return float The distance between the two points in the same unit as the earth radius as set by setEarthRadius() (default miles).
   * @access public
   */
  public function haversinDistance( $lat1, $long1, $lat2, $long2 )
  {
    $lat1 = deg2rad( $lat1 );
    $lat2 = deg2rad( $lat2 );
    $long1 = deg2rad( $long1);
    $long2 = deg2rad( $long2);

    $dlong = $long2 - $long1;
    $dlat = $lat2 - $lat1;

    $sinlat = sin( $dlat/2 );
    $sinlong = sin( $dlong/2 );

    $a = ($sinlat * $sinlat) + cos( $lat1 ) * cos( $lat2 ) * ($sinlong * $sinlong);
    $c = 2 * asin( min( 1, sqrt( $a ) ));

    return self::EARTH_RADIUS_MILES * $c;
  }

  /**
   * Find the distance between two latitude and longitude points using the
   * spherical law of cosines.
   *
   * @param float $lat1 The first coordinate's latitude
   * @param float $ong1 The first coordinate's longitude
   * @param float $lat2 The second coordinate's latitude
   * @param float $long2 The second coordinate's longitude
   * @return float The distance between the two points in the same unit as the earth radius as set by setEarthRadius() (default miles).
   * @access public
   */
  public function sphericalLawOfCosinesDistance( $lat1, $long1, $lat2, $long2 )
  {
    $lat1 = deg2rad( $lat1 );
    $lat2 = deg2rad( $lat2 );
    $long1 = deg2rad( $long1);
    $long2 = deg2rad( $long2);

    return self::EARTH_RADIUS_MILES * acos(
        sin( $lat1 ) * sin( $lat2 ) +
        cos( $lat1 ) * cos( $lat2 ) * cos( $long2 - $long1 )
      );
  }
}
