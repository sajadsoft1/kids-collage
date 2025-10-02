<?php

declare(strict_types=1);

namespace App\Enums;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="BooleanEnum",
 *     @OA\Property(property="value", type="boolean", default=1, enum={1, 0}),
 *     @OA\Property(property="label", type="string", default="Enable"),
 *     @OA\Property(property="color", type="string", default="success"),
 * ),
 * @OA\Schema(
 *     schema="BannerSizeEnum",
 *     @OA\Property(property="value", type="string", enum={"1x1", "16x9", "4x3"}),
 *     @OA\Property(property="label", type="string"),
 * ),
 * @OA\Schema(
 *     schema="CardStatusEnum",
 *     @OA\Property(property="value", type="string", enum={"draft", "active", "completed", "archived"}),
 *     @OA\Property(property="label", type="string"),
 *     @OA\Property(property="color", type="string"),
 *     @OA\Property(property="icon", type="string"),
 * ),
 * @OA\Schema(
 *     schema="CardTypeEnum",
 *     @OA\Property(property="value", type="string", enum={"note", "task", "bug", "feature", "call", "meeting", "email", "other"}),
 *     @OA\Property(property="label", type="string"),
 *     @OA\Property(property="color", type="string"),
 *     @OA\Property(property="icon", type="string"),
 * ),
 * @OA\Schema(
 *     schema="CategoryTypeEnum",
 *     @OA\Property(property="value", type="string", enum={"blog", "portfolio", "faq"}),
 *     @OA\Property(property="label", type="string"),
 * ),
 * @OA\Schema(
 *     schema="CommentTypeEnum",
 *     @OA\Property(property="value", type="string", enum={"normal", "special"}),
 *     @OA\Property(property="title", type="string"),
 * ),
 * @OA\Schema(
 *     schema="GenderEnum",
 *     @OA\Property(property="value", type="integer", enum={0, 1}),
 *     @OA\Property(property="label", type="string"),
 * ),
 * @OA\Schema(
 *     schema="NoificationTypeEnum",
 *     @OA\Property(property="value", type="string", enum={"auth_register", "auth_confirm", "auth_forgot_password", "auth_welcome"}),
 *     @OA\Property(property="label", type="string"),
 * ),
 * @OA\Schema(
 *     schema="PageTypeEnum",
 *     @OA\Property(property="value", type="string", enum={"rules", "about-us"}),
 *     @OA\Property(property="label", type="string"),
 * ),
 * @OA\Schema(
 *     schema="PriorityEnum",
 *     @OA\Property(property="value", type="string", enum={"low", "medium", "high", "urgent"}),
 *     @OA\Property(property="label", type="string"),
 *     @OA\Property(property="color", type="string"),
 *     @OA\Property(property="icon", type="string"),
 * ),
 * @OA\Schema(
 *     schema="RoleEnum",
 *     @OA\Property(property="value", type="string", enum={"developer", "admin"}),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="title", type="string"),
 * ),
 * @OA\Schema(
 *     schema="SeoRobotsMetaEnum",
 *     @OA\Property(property="value", type="string", enum={"index_follow", "noindex_nofollow", "noindex_follow"}),
 *     @OA\Property(property="label", type="string"),
 * ),
 * @OA\Schema(
 *     schema="SettingEnum",
 *     @OA\Property(property="value", type="string", enum={"product", "general", "integration_sync", "notification", "sale", "security"}),
 *     @OA\Property(property="label", type="string"),
 *     @OA\Property(property="help", type="string"),
 *     @OA\Property(property="hint", type="string"),
 * ),
 * @OA\Schema(
 *     schema="SliderPositionEnum",
 *     @OA\Property(property="value", type="string", enum={"top", "middle", "bottom"}),
 *     @OA\Property(property="label", type="string"),
 * ),
 * @OA\Schema(
 *     schema="SocialMediaPositionEnum",
 *     @OA\Property(property="value", type="string", enum={"all", "header", "footer"}),
 *     @OA\Property(property="label", type="string"),
 * ),
 * @OA\Schema(
 *     schema="TagTypeEnum",
 *     @OA\Property(property="value", type="string", enum={"special"}),
 *     @OA\Property(property="label", type="string"),
 * ),
 * @OA\Schema(
 *     schema="TicketDepartmentEnum",
 *     @OA\Property(property="value", type="string", enum={"finance_and_administration", "Sale", "technical"}),
 *     @OA\Property(property="label", type="string"),
 * ),
 * @OA\Schema(
 *     schema="TicketPriorityEnum",
 *     @OA\Property(property="value", type="integer", enum={1, 2, 3, 4}),
 *     @OA\Property(property="label", type="string"),
 * ),
 * @OA\Schema(
 *     schema="TicketStatusEnum",
 *     @OA\Property(property="value", type="string", enum={"open", "close"}),
 *     @OA\Property(property="label", type="string"),
 * ),
 * @OA\Schema(
 *     schema="YesNoEnum",
 *     @OA\Property(property="value", type="boolean", default=1, enum={1, 0}),
 *     @OA\Property(property="label", type="string"),
 *     @OA\Property(property="color", type="string"),
 * ),
 * * @OA\Schema(
 *     schema="UserTypeEnum",
 *     @OA\Property(property="value", type="string", default="user", enum={"teacher", "parent","user","employee"}),
 *     @OA\Property(property="label", type="string"),
 *
 * ),
 */
class ExtraSchema {}
