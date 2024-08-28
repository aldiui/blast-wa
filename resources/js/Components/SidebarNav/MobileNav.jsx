import {
    Avatar,
    Box,
    Flex,
    HStack,
    Icon,
    IconButton,
    Menu,
    MenuButton,
    MenuItem,
    MenuList,
    Text,
    VStack,
    useColorModeValue,
} from "@chakra-ui/react";
import {
    Bars3Icon,
    BellIcon,
    ChevronDownIcon,
} from "@heroicons/react/16/solid";
import { Link } from "@inertiajs/react";
import React from "react";

const MobileNav = ({ onOpen, auth, ...rest }) => {
    return (
        <Flex
            ml={{ base: 0, md: 60 }}
            px={{ base: 4, md: 4 }}
            height="20"
            alignItems="center"
            bg={useColorModeValue("white", "gray.900")}
            borderBottomWidth="1px"
            borderBottomColor={useColorModeValue("gray.200", "gray.700")}
            justifyContent={{ base: "space-between", md: "flex-end" }}
            {...rest}
        >
            <IconButton
                display={{ base: "flex", md: "none" }}
                onClick={onOpen}
                variant="outline"
                size={"sm"}
                aria-label="open menu"
                icon={<Icon as={Bars3Icon} w={5} h={5} />}
            />

            <Text
                display={{ base: "flex", md: "none" }}
                fontWeight={"extrabold"}
                fontSize={"xl"}
                color="green.500"
            >
                Wa Blast
            </Text>

            <HStack spacing={{ base: "0", md: "6" }}>
                <IconButton
                    size="lg"
                    variant="ghost"
                    aria-label="open menu"
                    icon={<Icon as={BellIcon} w={5} h={5} />}
                />
                <Flex alignItems={"center"}>
                    <Menu>
                        <MenuButton
                            py={2}
                            transition="all 0.3s"
                            _focus={{ boxShadow: "none" }}
                        >
                            <HStack>
                                <Avatar
                                    size={"sm"}
                                    name={auth.user?.nama}
                                    src={
                                        auth.user.avatar
                                            ? `${auth.user.avatar}`
                                            : null
                                    }
                                    alt="avatar"
                                />
                                <VStack
                                    display={{ base: "none", md: "flex" }}
                                    alignItems="flex-start"
                                    spacing="1px"
                                    ml="2"
                                >
                                    <Text fontSize="sm">{auth.user.nama}</Text>
                                    <Text fontSize="xs" color="gray.600">
                                        {auth.user.role}
                                    </Text>
                                </VStack>
                                <Box display={{ base: "none", md: "flex" }}>
                                    <ChevronDownIcon />
                                </Box>
                            </HStack>
                        </MenuButton>
                        <MenuList
                            bg={useColorModeValue("white", "gray.900")}
                            borderColor={useColorModeValue(
                                "gray.200",
                                "gray.700"
                            )}
                        >
                            <MenuItem
                                as={Link}
                                fontWeight={"semibold"}
                                href="/logout"
                                color={"red.500"}
                            >
                                Logout
                            </MenuItem>
                        </MenuList>
                    </Menu>
                </Flex>
            </HStack>
        </Flex>
    );
};

export default MobileNav;
