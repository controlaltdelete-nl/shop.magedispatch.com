/*
 *     ______            __             __
 *    / ____/___  ____  / /__________  / /
 *   / /   / __ \/ __ \/ __/ ___/ __ \/ /
 *  / /___/ /_/ / / / / /_/ /  / /_/ / /
 *  \______________/_/\__/_/   \____/_/
 *     /   |  / / /_
 *    / /| | / / __/
 *   / ___ |/ / /_
 *  /_/ _|||_/\__/ __     __
 *     / __ \___  / /__  / /____
 *    / / / / _ \/ / _ \/ __/ _ \
 *   / /_/ /  __/ /  __/ /_/  __/
 *  /_____/\___/_/\___/\__/\___/
 *
 * Copyright www.controlaltdelete.dev
 */

import { test, expect } from '@playwright/test';
import Checkout from "./Actions/Checkout";

test('Can enter shipping address on Checkout', async ({ page }) => {
    await new Checkout(page).visit();

    const shipping = page.locator('#checkout-step-shipping');
    await shipping.getByLabel('E-mailadres').fill('user@example.com');
    await shipping.getByLabel('Voornaam').fill('John');
    await shipping.getByLabel('Achternaam').fill('Doe');
    await page.getByRole('textbox', { name: 'Postcode*' }).fill('1111bv');
    await shipping.getByLabel('Huisnummer en toevoeging').fill('18');

    await page.locator('.table-checkout-shipping-method tbody tr').first().click();

    await expect(await page.getByText('Paulus Emtinckweg 18')).toBeVisible()

    await page.locator('.primary').getByText('Volgende').click();

    await expect(await page.getByText('Factuur- en verzendadres zijn hetzelfde')).toBeVisible()
});
