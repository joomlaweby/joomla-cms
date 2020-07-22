<?php

/**
 * @package         Google Structured Data
 * @version         4.6.0 Free
 *
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2018 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

namespace GSD;

defined('_JEXEC') or die('Restricted Access');

/**
 *  Google Structured Data JSON generator
 */
class JSON
{
	/**
	 *  Content Type Data
	 *
	 *  @var  object
	 */
	private $data;

    /**
     *  List of available content types
     *
     *  @var  array
     */
    private $contentTypes = array(
        
        'article'
    );

	/**
	 *  Class Constructor
	 *
	 *  @param  object  $data
	 */
	public function __construct($data = null)
	{
		$this->setData($data);
	}

    /**
     *  Get Content Types List
     *
     *  @return  array
     */
    public function getContentTypes()
    {
        $types = $this->contentTypes;
        asort($types);

        // Move Custom Code option to the end
        if ($customCodeIndex = array_search('custom_code', $types))
        {
            unset($types[$customCodeIndex]);
            $types[] = 'custom_code';
        }

        return $types;
    }

	/**
	 *  Set Data
	 *
	 *  @param  array  $data
	 */
	public function setData($data)
	{
		if (!is_array($data))
		{
			return;
		}

		$this->data = new \JRegistry($data);
		return $this;
	}

	/**
	 *  Get Content Type result
	 *
	 *  @return  string
	 */
	public function generate()
	{
        $contentTypeMethod = 'contentType' . $this->data->get('contentType');

        // Make sure we have a valid Content Type
		if (!method_exists($this, $contentTypeMethod) || !$content = $this->$contentTypeMethod())
		{
            return;
		}

        // In case we have a string (See Custom Code), return the original content.
        if (is_string($content))
        {
            return $content;
        }

        // On PHP < 7.0, make sure the array is in UTF8 encoding to prevent JSON errors.
        if (version_compare(PHP_VERSION, '7.0.0', '<'))
        {
            $content = Helper::arrayToUTF8($content);
        }

        // Remove null and empty properties
        $content = $this->clean($content);

        // In case we have an array, transform it into JSON-LD format.
        // Always prepend the @context property
        $content = ['@context' => 'https://schema.org'] + $content;

return '
<script type="application/ld+json">
'
    // We do not use JSON_NUMERIC_CHECK here because it doesn't respect numbers starting with 0. 
    // Bug: https://bugs.php.net/bug.php?id=70680
    . json_encode($content, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . 
'
</script>';
    }
    
    /**
     * Filter resursively an array by removing empty, false and null properties while preserving 0 values.
     *
     * @param  array $input
     *
     * @return array
     */
    private function clean($input)
    { 
        foreach ($input as &$value) 
        {
            if (is_array($value)) 
            { 
                $value = self::clean($value);
            }
        }

        // We use a custom callback here because the default behavior of array_filter removes 0 values as well.
        return array_filter($input, function($value) {
            return ($value !== null && $value !== false && $value !== ''); 
        });
    }

    /**
     * Constructs the FAQ Snippet
     * 
     * @return  array
     */
    private function contentTypeFAQ()
    {
        $faq = $this->data->get('data');

        // If there are no FAQ data, return
        if (count($faq) == 0)
        {
            return;
        }

        $faqData = [];

        foreach ($faq as $item)
        {
            $faqData[] = [
                '@type' => 'Question',
                'name'  => $item['question'],
                'acceptedAnswer' => array(
                    '@type'      => 'Answer',
                    'text'       => $item['answer']
                )
            ];
        }

        return [
            '@type'      => 'FAQPage',
            'mainEntity' => $faqData
        ];
    }

    /**
     *  Constructs the Breadcrumbs Snippet
     *
     *  @return  array
     */
    private function contentTypeBreadcrumbs()
    {
        $crumbs = $this->data->get('crumbs');
        
        if (!is_array($crumbs))
        {
            return;
        }

        $crumbsData = [];

        foreach ($crumbs as $key => $value)
        {
            $crumbsData[] = [
                '@type'    => 'ListItem',
                'position' => ($key + 1),
                'name'     => $value->name,
                'item'     => $value->link
            ];
        }

        return [
            '@type'           => 'BreadcrumbList',
            'itemListElement' => $crumbsData
        ];
    }

    /**
     *  Constructs the Website schema with the following info:
     * 
     *  Site Name: https://developers.google.com/structured-data/site-name
     *  Sitelinks Searchbox: https://developers.google.com/search/docs/data-types/sitelinks-searchbox
     *
     *  @return  array
     */
    private function contentTypeWebsite()
    {
        $content = [
            '@type' => 'WebSite',
            'url'   => $this->data->get('site_url')
        ];

        // Site Name
        if ($this->data->get('site_name_enabled'))
        {
            $content = array_merge($content, [
                'name'  => $this->data->get('site_name'),
                'alternateName' => $this->data->get('site_name_alt')
            ]);
        }

        // Sitelinks Search
        if ($this->data->get('site_links_search'))
        {
            $content = array_merge($content, [
                'potentialAction' => [
                    '@type'       => 'SearchAction',
                    'target'      => $this->data->get('site_links_search'),
                    'query-input' => 'required name=search_term'  
                ]
            ]);
        }

        return $content;
    }

    /**
     *  Constructs Site Logo Snippet
     *  https://developers.google.com/search/docs/data-types/logo
     *
     *  @return  array
     */
    private function contentTypeLogo()
    {
        return [
            '@type' => 'Organization',
            'url'   => $this->data->get('url'),
            'logo'  => $this->data->get('logo')
        ];
    }

	/**
	 *  Constructs the Article Content Type
	 *
	 *  @return  array
	 */
	private function contentTypeArticle()
	{
        $content = [
            '@type' => 'Article',
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id'   => $this->data->get('url')
            ],
            'headline'    => $this->data->get('title'),
            'description' => $this->data->get('description'),
            'image' => [
                '@type'  => 'ImageObject',
                'url'    => $this->data->get('image')
            ]
        ];

		// Author
		if ($this->data->get('authorName'))
		{
            $content = array_merge($content, [
                'author' => [
                    '@type' => 'Person',
                    'name'  => $this->data->get('authorName')
                ]
            ]);
		}

		// Publisher
		if ($this->data->get('publisherName'))
		{
            $content = array_merge($content, [
                'publisher' => [
                    '@type' => 'Organization',
                    'name'  => $this->data->get('publisherName'),
                    'logo'  => [
                        '@type'  => 'ImageObject',
                        'url'    => $this->data->get('publisherLogo')
                    ]
                ]
            ]);  
		}

        return $this->addDate($content);
	}

    /**
	 *  Constructs the Social Profiles Snippet
	 *  https://developers.google.com/search/docs/data-types/social-profile-links
	 *
	 *  @return  array
	 */
	private function contentTypeSocialProfiles()
	{
        return [
            '@type'  => $this->data->get('type'),
            'name'   => $this->data->get('sitename'),
            'url'    => $this->data->get('siteurl'),
            'sameAs' => array_values((array) $this->data->get('links'))
        ];
	}

    
    
    /**
     *  Appends the aggregateRating property to object
     *
     *  @param  array  &$content
     */
    private function addRating(&$content)
    {
        if (!$this->data->get('ratingValue') || !$this->data->get('reviewCount'))
        {
            return;
        }

        return $content = array_merge($content, [
            'aggregateRating' => [
                '@type'       => 'AggregateRating',
                'ratingValue' => $this->data->get('ratingValue'),
                'reviewCount' => $this->data->get('reviewCount'),
                'worstRating' => $this->data->get('worstRating', 0),
                'bestRating'  => $this->data->get('bestRating', 5)
            ]
        ]);
    }

    /**
     *  Appends date properties to object
     *
     *  @param  array  &$content
     */
    private function addDate(&$content)
    {
        return $content = array_merge($content, [
            'datePublished' => $this->data->get('datePublished'),
            'dateCreated'   => $this->data->get('dateCreated'),
            'dateModified'  => $this->data->get('dateModified')
        ]);
    }
}

?>