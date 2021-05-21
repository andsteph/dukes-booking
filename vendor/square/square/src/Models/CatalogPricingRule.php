<?php

declare(strict_types=1);

namespace Square\Models;

/**
 * Defines how discounts are automatically applied to a set of items that match the pricing rule
 * during the active time period.
 */
class CatalogPricingRule implements \JsonSerializable
{
    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string[]|null
     */
    private $timePeriodIds;

    /**
     * @var string|null
     */
    private $discountId;

    /**
     * @var string|null
     */
    private $matchProductsId;

    /**
     * @var string|null
     */
    private $applyProductsId;

    /**
     * @var string|null
     */
    private $excludeProductsId;

    /**
     * @var string|null
     */
    private $validFromDate;

    /**
     * @var string|null
     */
    private $validFromLocalTime;

    /**
     * @var string|null
     */
    private $validUntilDate;

    /**
     * @var string|null
     */
    private $validUntilLocalTime;

    /**
     * @var string|null
     */
    private $excludeStrategy;

    /**
     * @var string[]|null
     */
    private $customerGroupIdsAny;

    /**
     * Returns Name.
     *
     * User-defined name for the pricing rule. For example, "Buy one get one
     * free" or "10% off".
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Sets Name.
     *
     * User-defined name for the pricing rule. For example, "Buy one get one
     * free" or "10% off".
     *
     * @maps name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * Returns Time Period Ids.
     *
     * A list of unique IDs for the catalog time periods when
     * this pricing rule is in effect. If left unset, the pricing rule is always
     * in effect.
     *
     * @return string[]|null
     */
    public function getTimePeriodIds(): ?array
    {
        return $this->timePeriodIds;
    }

    /**
     * Sets Time Period Ids.
     *
     * A list of unique IDs for the catalog time periods when
     * this pricing rule is in effect. If left unset, the pricing rule is always
     * in effect.
     *
     * @maps time_period_ids
     *
     * @param string[]|null $timePeriodIds
     */
    public function setTimePeriodIds(?array $timePeriodIds): void
    {
        $this->timePeriodIds = $timePeriodIds;
    }

    /**
     * Returns Discount Id.
     *
     * Unique ID for the `CatalogDiscount` to take off
     * the price of all matched items.
     */
    public function getDiscountId(): ?string
    {
        return $this->discountId;
    }

    /**
     * Sets Discount Id.
     *
     * Unique ID for the `CatalogDiscount` to take off
     * the price of all matched items.
     *
     * @maps discount_id
     */
    public function setDiscountId(?string $discountId): void
    {
        $this->discountId = $discountId;
    }

    /**
     * Returns Match Products Id.
     *
     * Unique ID for the `CatalogProductSet` that will be matched by this rule. A match rule
     * matches within the entire cart, and can match multiple times. This field will always be set.
     */
    public function getMatchProductsId(): ?string
    {
        return $this->matchProductsId;
    }

    /**
     * Sets Match Products Id.
     *
     * Unique ID for the `CatalogProductSet` that will be matched by this rule. A match rule
     * matches within the entire cart, and can match multiple times. This field will always be set.
     *
     * @maps match_products_id
     */
    public function setMatchProductsId(?string $matchProductsId): void
    {
        $this->matchProductsId = $matchProductsId;
    }

    /**
     * Returns Apply Products Id.
     *
     * __Deprecated__: Please use the `exclude_products_id` field to apply
     * an exclude set instead. Exclude sets allow better control over quantity
     * ranges and offer more flexibility for which matched items receive a discount.
     *
     * `CatalogProductSet` to apply the pricing to.
     * An apply rule matches within the subset of the cart that fits the match rules (the match set).
     * An apply rule can only match once in the match set.
     * If not supplied, the pricing will be applied to all products in the match set.
     * Other products retain their base price, or a price generated by other rules.
     */
    public function getApplyProductsId(): ?string
    {
        return $this->applyProductsId;
    }

    /**
     * Sets Apply Products Id.
     *
     * __Deprecated__: Please use the `exclude_products_id` field to apply
     * an exclude set instead. Exclude sets allow better control over quantity
     * ranges and offer more flexibility for which matched items receive a discount.
     *
     * `CatalogProductSet` to apply the pricing to.
     * An apply rule matches within the subset of the cart that fits the match rules (the match set).
     * An apply rule can only match once in the match set.
     * If not supplied, the pricing will be applied to all products in the match set.
     * Other products retain their base price, or a price generated by other rules.
     *
     * @maps apply_products_id
     */
    public function setApplyProductsId(?string $applyProductsId): void
    {
        $this->applyProductsId = $applyProductsId;
    }

    /**
     * Returns Exclude Products Id.
     *
     * `CatalogProductSet` to exclude from the pricing rule.
     * An exclude rule matches within the subset of the cart that fits the match rules (the match set).
     * An exclude rule can only match once in the match set.
     * If not supplied, the pricing will be applied to all products in the match set.
     * Other products retain their base price, or a price generated by other rules.
     */
    public function getExcludeProductsId(): ?string
    {
        return $this->excludeProductsId;
    }

    /**
     * Sets Exclude Products Id.
     *
     * `CatalogProductSet` to exclude from the pricing rule.
     * An exclude rule matches within the subset of the cart that fits the match rules (the match set).
     * An exclude rule can only match once in the match set.
     * If not supplied, the pricing will be applied to all products in the match set.
     * Other products retain their base price, or a price generated by other rules.
     *
     * @maps exclude_products_id
     */
    public function setExcludeProductsId(?string $excludeProductsId): void
    {
        $this->excludeProductsId = $excludeProductsId;
    }

    /**
     * Returns Valid From Date.
     *
     * Represents the date the Pricing Rule is valid from. Represented in RFC 3339 full-date format (YYYY-
     * MM-DD).
     */
    public function getValidFromDate(): ?string
    {
        return $this->validFromDate;
    }

    /**
     * Sets Valid From Date.
     *
     * Represents the date the Pricing Rule is valid from. Represented in RFC 3339 full-date format (YYYY-
     * MM-DD).
     *
     * @maps valid_from_date
     */
    public function setValidFromDate(?string $validFromDate): void
    {
        $this->validFromDate = $validFromDate;
    }

    /**
     * Returns Valid From Local Time.
     *
     * Represents the local time the pricing rule should be valid from. Represented in RFC 3339 partial-
     * time format
     * (HH:MM:SS). Partial seconds will be truncated.
     */
    public function getValidFromLocalTime(): ?string
    {
        return $this->validFromLocalTime;
    }

    /**
     * Sets Valid From Local Time.
     *
     * Represents the local time the pricing rule should be valid from. Represented in RFC 3339 partial-
     * time format
     * (HH:MM:SS). Partial seconds will be truncated.
     *
     * @maps valid_from_local_time
     */
    public function setValidFromLocalTime(?string $validFromLocalTime): void
    {
        $this->validFromLocalTime = $validFromLocalTime;
    }

    /**
     * Returns Valid Until Date.
     *
     * Represents the date the Pricing Rule is valid until. Represented in RFC 3339 full-date format (YYYY-
     * MM-DD).
     */
    public function getValidUntilDate(): ?string
    {
        return $this->validUntilDate;
    }

    /**
     * Sets Valid Until Date.
     *
     * Represents the date the Pricing Rule is valid until. Represented in RFC 3339 full-date format (YYYY-
     * MM-DD).
     *
     * @maps valid_until_date
     */
    public function setValidUntilDate(?string $validUntilDate): void
    {
        $this->validUntilDate = $validUntilDate;
    }

    /**
     * Returns Valid Until Local Time.
     *
     * Represents the local time the pricing rule should be valid until. Represented in RFC 3339 partial-
     * time format
     * (HH:MM:SS). Partial seconds will be truncated.
     */
    public function getValidUntilLocalTime(): ?string
    {
        return $this->validUntilLocalTime;
    }

    /**
     * Sets Valid Until Local Time.
     *
     * Represents the local time the pricing rule should be valid until. Represented in RFC 3339 partial-
     * time format
     * (HH:MM:SS). Partial seconds will be truncated.
     *
     * @maps valid_until_local_time
     */
    public function setValidUntilLocalTime(?string $validUntilLocalTime): void
    {
        $this->validUntilLocalTime = $validUntilLocalTime;
    }

    /**
     * Returns Exclude Strategy.
     *
     * Indicates which products matched by a CatalogPricingRule
     * will be excluded if the pricing rule uses an exclude set.
     */
    public function getExcludeStrategy(): ?string
    {
        return $this->excludeStrategy;
    }

    /**
     * Sets Exclude Strategy.
     *
     * Indicates which products matched by a CatalogPricingRule
     * will be excluded if the pricing rule uses an exclude set.
     *
     * @maps exclude_strategy
     */
    public function setExcludeStrategy(?string $excludeStrategy): void
    {
        $this->excludeStrategy = $excludeStrategy;
    }

    /**
     * Returns Customer Group Ids Any.
     *
     * A list of IDs of customer groups, the members of which are eligible for discounts specified in this
     * pricing rule.
     * Notice that a group ID is generated by the Customers API.
     * If this field is not set, the specified discount applies to matched products sold to anyone whether
     * the buyer
     * has a customer profile created or not. If this `customer_group_ids_any` field is set, the specified
     * discount
     * applies only to matched products sold to customers belonging to the specified customer groups.
     *
     * @return string[]|null
     */
    public function getCustomerGroupIdsAny(): ?array
    {
        return $this->customerGroupIdsAny;
    }

    /**
     * Sets Customer Group Ids Any.
     *
     * A list of IDs of customer groups, the members of which are eligible for discounts specified in this
     * pricing rule.
     * Notice that a group ID is generated by the Customers API.
     * If this field is not set, the specified discount applies to matched products sold to anyone whether
     * the buyer
     * has a customer profile created or not. If this `customer_group_ids_any` field is set, the specified
     * discount
     * applies only to matched products sold to customers belonging to the specified customer groups.
     *
     * @maps customer_group_ids_any
     *
     * @param string[]|null $customerGroupIdsAny
     */
    public function setCustomerGroupIdsAny(?array $customerGroupIdsAny): void
    {
        $this->customerGroupIdsAny = $customerGroupIdsAny;
    }

    /**
     * Encode this object to JSON
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        $json = [];
        $json['name']                = $this->name;
        $json['time_period_ids']     = $this->timePeriodIds;
        $json['discount_id']         = $this->discountId;
        $json['match_products_id']   = $this->matchProductsId;
        $json['apply_products_id']   = $this->applyProductsId;
        $json['exclude_products_id'] = $this->excludeProductsId;
        $json['valid_from_date']     = $this->validFromDate;
        $json['valid_from_local_time'] = $this->validFromLocalTime;
        $json['valid_until_date']    = $this->validUntilDate;
        $json['valid_until_local_time'] = $this->validUntilLocalTime;
        $json['exclude_strategy']    = $this->excludeStrategy;
        $json['customer_group_ids_any'] = $this->customerGroupIdsAny;

        return array_filter($json, function ($val) {
            return $val !== null;
        });
    }
}
