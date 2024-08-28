import React, { useRef, useState } from "react";
import { Head, Link, router } from "@inertiajs/react";
import { pickBy } from "lodash";
import {
    Button,
    Card,
    CardBody,
    CardHeader,
    Flex,
    FormControl,
    FormLabel,
    Heading,
    Icon,
    Input,
    Select,
    Text,
} from "@chakra-ui/react";
import Pagination from "../../../Components/Pagination";
import DataTable from "../../../Components/Datatable";
import { PlusIcon } from "@heroicons/react/16/solid";
import AdminLayout from "../../../Layouts/AdminLayout ";

const Siswa = ({ auth, sessions, siswa, kelas }) => {
    const perpage = useRef(siswa.per_page);
    const [isLoading, setIsLoading] = useState(false);
    const [search, setSearch] = useState("");
    const [selectedKelas, setSelectedKelas] = useState("");

    const handleChangePerPage = (e) => {
        perpage.current = parseInt(e.target.value);
        getData();
    };

    const handleChangeKelas = (e) => {
        setSelectedKelas(e.target.value);
        getData(e.target.value);
    };

    const handleSearch = (e) => {
        e.preventDefault();
        getData();
    };

    const getData = (kelas = selectedKelas) => {
        setIsLoading(true);
        router.get(
            "/siswa",
            pickBy({
                perpage: perpage.current,
                search: search,
                id_kelas : kelas
            }),
            {
                onFinish: () => setIsLoading(false),
                preserveScroll: true,
                preserveState: true,
            }
        );
    };

    const calculateIndex = (index) => siswa.from + index;

    const columns = [
        { header: "#", accessor: null, width: "5" },
        { header: "Nama", accessor: "nama" },
        { header: "Kelas", accessor: "kelas.nama" },
        { header: "Orang Tua", accessor: "orang_tua" },
        { header: "No Telepon", accessor: "no_telepon" },
        {
            header: "Aksi",
            accessor: "Aksi",
            width: "10",
            uri: "/siswa",
        },
    ];

    return (
        <AdminLayout auth={auth} sessions={sessions}>
            <Head title="Siswa" />
            <Card p={2} w="full" h={["auto", "full"]}>
                <CardHeader
                    display={"flex"}
                    justifyContent={"space-between"}
                    alignItems={"center"}
                >
                    <Heading size="md" fontWeight="bold">
                        Data Siswa
                    </Heading>
                    <Button
                        as={Link}
                        href="/siswa/create"
                        colorScheme="green"
                        size={"sm"}
                    >
                        <Icon as={PlusIcon} name="plus" mr={2} /> Tambah
                    </Button>
                </CardHeader>
                <CardBody>
                    <FormControl mb={3} maxW={["full", "xs"]}>
                        <FormLabel htmlFor="id_kelas" fontSize={"sm"}>
                            Kelas
                            <Text display={"inline"} color="red">
                                *
                            </Text>
                        </FormLabel>
                        <Select
                            id="id_kelas"
                            value={selectedKelas}
                            onChange={handleChangeKelas}
                        >
                            <option value="">Semua</option>
                            {kelas.map((kls) => (
                                <option key={kls.id} value={kls.id}>
                                    {kls.nama}
                                </option>
                            ))}
                        </Select>
                    </FormControl>
                    <Flex
                        flexDir={{ base: "column", md: "row" }}
                        justifyContent={"space-between"}
                        alignItems={"center"}
                        gap={4}
                        mb={4}
                    >
                        <Select
                            id="perpage"
                            size={"sm"}
                            name="perpage"
                            width="auto"
                            value={perpage.current}
                            onChange={handleChangePerPage}
                        >
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="250">250</option>
                            <option value="500">500</option>
                        </Select>
                        <Flex as={"form"} gap={2} onSubmit={handleSearch}>
                            <Input
                                size={"sm"}
                                id="search"
                                name="search"
                                type="text"
                                width="auto"
                                value={search}
                                onChange={(e) => setSearch(e.target.value)}
                            />
                            <Button
                                type="submit"
                                colorScheme="blue"
                                variant="outline"
                                size={"sm"}
                                isLoading={isLoading}
                                loadingText="Cari"
                            >
                                Cari
                            </Button>
                        </Flex>
                    </Flex>
                    <DataTable
                        columns={columns}
                        data={siswa.data}
                        isLoading={isLoading}
                        calculateIndex={calculateIndex}
                    />
                    <Flex
                        gap={4}
                        flexDir={{ base: "column", md: "row" }}
                        justifyContent={{ base: "center", md: "space-between" }}
                        alignItems="center"
                        mt={4}
                    >
                        <Text color="gray.500" fontSize="sm">
                            Showing {siswa.from ?? 0} to {siswa.to ?? 0} of{" "}
                            {siswa.total ?? 0}
                        </Text>
                        <Pagination links={siswa.links ?? []} />
                    </Flex>
                </CardBody>
            </Card>
        </AdminLayout>
    );
};

export default Siswa;
